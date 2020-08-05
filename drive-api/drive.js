const CLIENT_ID = '482231023052-5guceakfvvmdjabd2jtoitb42iuibp8u.apps.googleusercontent.com';
const API_KEY = 'AIzaSyDrpRp3-N2XU1rGB7R1tWljypOyj5XAvA4';

const DISCOVERY_DOCS = ['https://www.googleapis.com/discovery/v1/apis/drive/v3/rest'];
let SCOPES = 'https://www.googleapis.com/auth/drive.metadata.readonly https://www.googleapis.com/auth/drive.file';

const FIELDS = ['id', 'name', 'version', 'webViewLink', 'modifiedTime', 'size', 'headRevisionId'];
const SCHED_FOLDER_ID = '1REOlgGzUqa97tRfGHsqjhtu5ARcSlAip';
// const SCHED_FOLDER_ID = '11xoddxmuyPIftKVThhkFNpV9VeH2YAzE'; // Dev folder

const LIVE_SCHEDULE_NAME = 'current-schedule-yoga-balance.pdf';
let LIVE_SCHEDULE_ID = null;

const driveAuthButton = document.getElementById('drive_auth_button');
const monthInput = document.getElementById('month_input');
const fileInput = document.getElementById('file_input');
const overwriteCheckbox = document.getElementById('overwrite_current');
const submitButton = document.getElementById('submit_button');
const errorBox = document.getElementById('error_box');
const statusBox = document.getElementById('status_box');
const fileViewer = document.getElementById('file_viewer');

submitButton.addEventListener('click', submitUpload);
driveAuthButton.addEventListener('click', () => {
    if (gapi.auth2.getAuthInstance().isSignedIn.get()) {
        gapi.auth2.getAuthInstance().signOut();
    } else {
        gapi.auth2.getAuthInstance().signIn();
    }
});

function gapiReady() {
    gapi.load('client:auth2', initClient);
}

function isSignedIn() {
    return gapi.auth2.getAuthInstance().isSignedIn.get();
}

async function initClient() {
    try {
        await gapi.client.init({
            apiKey: API_KEY,
            clientId: CLIENT_ID,
            discoveryDocs: DISCOVERY_DOCS,
            scope: SCOPES
        });
    } catch (e) {
        console.dir(e);
        console.error('Could not initialize.');
        return;
    }

    // Listen for sign-in state changes
    gapi.auth2.getAuthInstance().isSignedIn.listen(updateSignInStatus);

    // Match the initial state
    updateSignInStatus();
}

function updateSignInStatus() {
    if (isSignedIn()) {
        driveAuthButton.innerHTML = 'Sign out';
    } else {
        driveAuthButton.innerHTML = 'Sign in';
    }
    listFiles();
}

function setStatus(text) {
    statusBox.innerHTML = text;
}

function setError(text) {

    statusBox.innerHTML = '';

    const span = document.createElement('span');
    span.style.color = 'red';
    span.style.fontWeight = 'bold';
    span.innerHTML = text;

    statusBox.appendChild(span);
}

function addCol(row, innerHtml, type = 'td') {
    let col = document.createElement(type);
    col.innerHTML = innerHtml;
    col.align = 'center';

    row.appendChild(col);
    return col;
}

function clearFileTable() {

    fileViewer.innerHTML = '';

    let header = document.createElement('tr');

    addCol(header, 'Name', 'th');
    addCol(header, 'Version', 'th');
    addCol(header, 'Last Modified', 'th');
    addCol(header, 'Size', 'th');

    fileViewer.appendChild(header);

    return header;
}

function noResults(text) {

    text = text || 'No files found';

    let header = clearFileTable();

    let row = document.createElement('tr');
    let col = document.createElement('td');
    col.colSpan = header.childElementCount;
    col.align = 'center';
    col.innerHTML = text;

    row.appendChild(col);
    fileViewer.appendChild(row);

    return row;
}

async function listFiles() {
    let response;
    let files;

    if (!isSignedIn()) {
        noResults('Please sign in');
        return;
    }

    setStatus('Refreshing...');
    clearFileTable();

    try {
        // Fetch all PDFs included in the schedule folder
        response = await gapi.client.drive.files.list({
            'q': `'${SCHED_FOLDER_ID}' in parents and not trashed and mimeType='application/pdf'`,
            'orderBy': 'modifiedTime desc',
            'pageSize': 100,
            'fields': `nextPageToken, files(${FIELDS.join(', ')})`
        });
    } catch(e) {
        console.error('Could not fetch file list.', e);
        noResults('Could not fetch file list: ' + e);
        return;
    }

    files = response.result.files;
    if (files && files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            let file = files[i];

            // Record the ID of the live schedule
            // Assume it will be in the first 100
            if (file['name'] === LIVE_SCHEDULE_NAME) {
                LIVE_SCHEDULE_ID = file['id'];
                console.log(`LIVE ID: ${LIVE_SCHEDULE_ID}`);
            }

            let row = document.createElement('tr');
            let d = new Date(file['modifiedTime']);

            row.id = file['id'];
            addCol(row, `<a href="${file['webViewLink']}" target="_blank">${file['name']}</a>`).align = 'left';
            addCol(row, file['version']);
            addCol(row, `${d.toLocaleString()}`);
            addCol(row, `${Math.round(file['size'] / 1024.0)} KB`).align = 'right';

            fileViewer.appendChild(row);
        }
    } else {
        noResults();
    }

    setStatus('&nbsp;');
}

function formIsInvalid() {

    if (!isSignedIn()) {
        return 'Not signed in';
    }

    if (fileInput.files.length == 0) {
        return 'Missing file';
    }

    return false;
}

async function submitUpload() {

    if (formIsInvalid()) {
        const reason = formIsInvalid();
        setError(reason);
        return;
    }

    const d = new Date();
    const year = d.getFullYear();

    let month = monthInput.value;
    if (month.length === 0) {
        month = d.toLocaleString('default', { month: 'long' }).toLowerCase();
    }

    const backupFilename = `${year}-${month}-schedule-yoga-balance.pdf`;

    // Have to create the file before we can update it
    if (LIVE_SCHEDULE_ID === null) {
        console.log('Live schedule file not found. Creating...');

        setStatus(`Creating ${LIVE_SCHEDULE_NAME}...`);

        response = await uploadFile(LIVE_SCHEDULE_NAME, null);
        LIVE_SCHEDULE_ID = response.id;

        console.log(`LIVE ID: ${LIVE_SCHEDULE_ID}`);
    }

    setStatus(`Uploading ${backupFilename}...`);
    await uploadFile(backupFilename, fileInput.files[0]);

    if (overwriteCheckbox.checked) {
        setStatus(`Uploading ${LIVE_SCHEDULE_NAME}...`);
        await updateFile(LIVE_SCHEDULE_ID, fileInput.files[0]);
    }

    await listFiles();
}

async function uploadFile(name, file) {

    const metadata = {
        'name': name,
        'mimeType': 'application/pdf',
        'parents': [SCHED_FOLDER_ID]
    };

    return await makeFileRequest(metadata, file);
}

async function updateFile(id, file) {

    const metadata = {
        'mimeType': 'application/pdf'
    };

    return await makeFileRequest(metadata, file, id, 'PATCH');
}

async function makeFileRequest(metadata, file, id, method) {

    id = id || '';
    method = method || 'POST';

    const form = new FormData();
    form.append('metadata', new Blob([JSON.stringify(metadata)], { type: 'application/json' }));
    form.append('file', file);

    let response;
    try {
        // response = await gapi.client.request({
        //     'path': `upload/drive/v3/files/${id}`,
        //     'method': method,
        //     'params': {
        //         'uploadType': 'multipart'
        //     },
        //     'body': form
        // });

        const accessToken = gapi.auth.getToken().access_token;
        response = await fetch(`https://www.googleapis.com/upload/drive/v3/files/${id}?uploadType=multipart`, {
            method: method,
            headers: new Headers({ 'Authorization': 'Bearer ' + accessToken }),
            body: form,
        });

        json = await response.json();
    } catch (e) {
        console.error(e);
        errorBox.innerHTML = e;
    }

    console.dir(response);
    console.dir(json);

    return json;
}