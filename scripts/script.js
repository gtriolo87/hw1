/*
const IMG_CHECKED = 'images/checked.png';
const IMG_UNCHECKED = 'images/unchecked.png';
*/
const API_KEY_GOOGLE = 'AIzaSyA18CoxcFIRxukRKRhyLQentY99KGJOnEQ';
const URL_VIDEO_YOUTUBE = 'https://www.youtube.com/watch?v=';

/* esempio: https://todoist.com/oauth/authorize?client_id=0123456789abcdef&scope=data:read,data:delete&state=secretstring */

/* -------------------------------------------------------------
GESTIONE DEI PROGETTI PRESI DA SERVIZIO ToDoIst 
Si chiama un php interno che fa richieste API REST verso il
servizio esterno
------------------------------------------------------------- */
function onToDoJson(esito) {
    const sectionTodo = document.querySelector('#job-ToDo');
    if (esito[0]===false){
        console.log(esito[1]);
    } else {
        projects=esito[1];
        for (const project of projects) {
        if (project.name !== 'Inbox') {
            const div = document.createElement('div');
            const link = document.createElement('a');
            div.classList.add('newJob');
            link.href = project.url;
            link.textContent = project.name;
            div.append(link);
            sectionTodo.append(div);
        }
        }
        if (projects.length > 1) {
            document.querySelector('#ToDo').classList.remove('hidden');
        }
    }
    
}

function onToDoResponse(response) {
    return response.json();
}

function onToDoError(response) {
    console.log('Errore: ' + response.text());
}

fetch("classes/retriveToDo.php",).then(onToDoResponse, onToDoError).then(onToDoJson);
/* -------------------------------------------------------------
CONCLUSA GESTIONE DEI PROGETTI PRESI DA SERVIZIO ToDoIst 
Lo facciamo all'apertura della pagina e basta.
------------------------------------------------------------- */

/* -------------------------------------------------------------
GESTIONE OVERLAY
Può essere popolato da Google Maps o da YouTube 
------------------------------------------------------------- */
function showOverlay() {
    const layerOverlay = document.querySelector('#overlay');
    let noResults;
    document.body.classList.add('no-scroll');
    layerOverlay.style.top = window.pageYOffset + 'px';
    layerOverlay.classList.remove('hidden');
}
function insertMap(object) {
    const lat = object.parentNode.dataset.jobLat;
    const long = object.parentNode.dataset.jobLong;
    const restUrl = 'https://www.google.com/maps/embed/v1/view?key=' + API_KEY_GOOGLE + '&center=' + lat + ',' + long + '&zoom=18&maptype=satellite';  

    const overlayContent = document.querySelector('#overlay .content');
    const iframe = document.createElement('iframe');
    iframe.src = restUrl;
    iframe.referrerPolicy = 'no-referrer-when-downgrade';
    overlayContent.append(iframe);
    showOverlay();
}

function readVideos(response) {
    const overlayContent = document.querySelector('#overlay .content');
    if (response[0]){
        if(response[1]!==null){
            for (const video of response[1].items){
                const div = document.createElement('div');
                const image = document.createElement('img');
                const link = document.createElement('a');
                div.classList.add('previewVideo');
                image.src = video.snippet.thumbnails.default.url;
                link.href = URL_VIDEO_YOUTUBE + video.id.videoId;
                link.textContent = video.snippet.title;
                div.append(image);
                div.append(link);
                overlayContent.append(div);
            }
            if (response[1].items.length > 0) {
                showOverlay();
            } else {
                const div = document.createElement('div');
                const h1 = document.createElement('a');
                div.classList.add('previewVideo');
                h1.textContent="Non sono presenti video con le keywords inserite";
                div.append(h1);
                overlayContent.append(div);
                //console.log('Nessun video trovato!');
                showOverlay();
            }
        } else{
            console.log('Nessun video trovato!');
        }
        
    } else{
        console.log(response[1]);
    }
    
}
function onVideoResponse(response) {
    return response.json();
}
function onVideoError(response) {
    console.log('Errore: ' + response);
}
function insertVideo(object) {
    const keywords = encodeURIComponent(object.parentNode.dataset.jobKeywords);
    //console.log(keywords);
    fetch("classes/retriveYouTubeVideo.php?keywords="+keywords).then(onVideoResponse, onVideoError).then(readVideos);
}

/* -------------------------------------------------------------
FUNZIONE APERTURA OVERLAY 
A seconda che l'apertura arrivi dal thumbnail o dal job intro
avvia delle funzioni diverse
------------------------------------------------------------- */
function openOverlay(event) {
    if (event.currentTarget.className==='job-thumbnail') {
        insertMap(event.currentTarget);
    }
    else {
        insertVideo(event.currentTarget);
    }
    event.stopPropagation();
}

/* -------------------------------------------------------------
GESTIONE DEI LIKE

------------------------------------------------------------- */
function onLikeJson(response){
    switch (response['result']) {
        case 0:
            console.log("Errore: "+response['message']);
            break;
        case 1:
            //console.log(response['message']);
            const emToUpdate=document.querySelector('div[data-job-id="' + response['jobId'] + '"] .job-like em');
            emToUpdate.textContent=response['jobNLikes'];
            break;
        case 2:
            //console.log(response['message']);
            const divToUpdate=document.querySelector('div[data-job-id="' + response['jobId'] + '"] .job-like');
            if (response['esito']){
                divToUpdate.classList.add("liked");
            }else{
                divToUpdate.classList.remove("liked");
            }
            break;
        case 3:
            //console.log(response['message']);
            checkLike();
            break;
        default:
            console.log("Tipo non corrispondente: "+response['message']);
            break;
    }
    if (response['result']===0){
    } else{
    }
}

function onLikeResponse(response){
    return response.json();
}
function checkLike(event){
    let userId=0;
    
    const divLogin= document.getElementById("divLogged");
    if (divLogin.className==="hidden"){
        //console.log("Nessun Utente loggato. Non posso inserire like");
    } else{
        userId=divLogin.dataset.userId;
    }
        

    if (event!==undefined){
        //console.log(event.currentTarget.textContent);
        const jobId = event.currentTarget.parentNode.dataset.jobId;
        if (divLogin.className==="hidden"){
            console.log("Nessun Utente loggato. Non posso inserire like");
            return false;
        }
        //console.log("provo con job "+jobId+" e user "+userId);
        fetch("classes/checkLikes.php?type=3&userId="+userId+"&jobId="+jobId).then(onLikeResponse).then(onLikeJson);
    }else{
        //Se la funzione è stata chiamata senza parametri vuol dire che è per il controllo
        //Prelevo sia il numero di like e se l'utente ha fatto like per il job
        const listLike=document.querySelectorAll(".job-like");
        for (like of listLike){
            const jobId=like.parentNode.dataset.jobId;
            fetch("classes/checkLikes.php?type=1&jobId="+jobId).then(onLikeResponse).then(onLikeJson);
            fetch("classes/checkLikes.php?type=2&userId="+userId+"&jobId="+jobId).then(onLikeResponse).then(onLikeJson);
        }
    }
}

/* -------------------------------------------------------------
GESTIONE DEI PROGETTI INTERNI 
Si chiama un php interno che restituisce JSON con elenco lavori
per creare la pagina. La funziona sarà utilizzata anche per
effettuare ricerche nella pagina
------------------------------------------------------------- */
function openJob(event){
    const jobId = event.currentTarget.parentNode.parentNode.dataset.jobId;
    window.open("job.php?job_id="+jobId,"_self");
    event.stopPropagation();
}
//Funzione che, passato un array Job con i dati presi da DB, crea il div completo
//del Job includendo anche i listener per la gestione dei click
function createHtmlJobElement(aJob){
    //prelevo la sezione da popolare
    const sectionJobList = document.querySelector('#job-list');

    //creo il div generale e lo popolo con le proprietà previste
    let divJob = document.createElement('div');
    divJob.classList.add("job");
    divJob.dataset.jobId = aJob['jobId'];
    divJob.dataset.jobLat = aJob['jobLat'];
    divJob.dataset.jobLong = aJob['jobLong'];
    divJob.dataset.jobKeywords = aJob['jobKeywords'];
    divJob.dataset.jobVideo = aJob['jobHasVideo'];

    //Creo il primo blocco div e gli oggetti interni JOB INTRO
        let div = document.createElement('div');
        div.classList.add("job-intro");
            let img = document.createElement('img');
            img.src=aJob['jobImage'];
            let h3Title = document.createElement('h3');
            h3Title.classList.add("job-title");
            h3Title.textContent=aJob['jobTitle'];
            h3Title.addEventListener('click',openJob);
        div.appendChild(img);
        div.appendChild(h3Title);
        //definisco il comportamento al click del div jobIntro
        div.addEventListener('click', openOverlay);
    //Lo unisco al blocco principale
    divJob.appendChild(div);

    //Creo il secondo blocco div e gli oggetti interni JOB thumbnail
        div = document.createElement('div');
        div.classList.add("job-thumbnail");
        //definisco il comportamento al click del div jobThumbnail
        div.addEventListener('click', openOverlay);
    //Lo unisco al blocco principale
    divJob.appendChild(div);

    //Creo il terzo blocco div e gli oggetti interni JOB DESCRIPTION
        div = document.createElement('div');
        div.classList.add("job-description");
            const divInfo=document.createElement('div');
            divInfo.classList.add('job-info');
                let parag = document.createElement('p');
                    //Prima Riga
                    let strong = document.createElement('strong');
                    let em = document.createElement('em');
                    let br = document.createElement('br');
                    strong.textContent="Anno Conclusione:";
                    if (aJob['jobEnded']){
                        em.textContent=aJob['jobEndingYear'];
                    } else{
                        em.textContent="In Corso";
                    }
                parag.appendChild(strong);
                parag.appendChild(em);
                parag.appendChild(br);
                    //Seconda Riga
                    strong = document.createElement('strong');
                    em = document.createElement('em');
                    br = document.createElement('br');
                    strong.textContent="PLC/SCADA:";
                    em.textContent=aJob['jobDevice'];
                parag.appendChild(strong);
                parag.appendChild(em);
                parag.appendChild(br);
                    //Terza Riga
                    strong = document.createElement('strong');
                    em = document.createElement('em');
                    br = document.createElement('br');
                    strong.textContent="Cliente:";
                    em.textContent=aJob['jobCustomer'];
                parag.appendChild(strong);
                parag.appendChild(em);
                parag.appendChild(br);
            divInfo.appendChild(parag);
        div.appendChild(divInfo);
            parag=document.createElement('p');
            parag.textContent=aJob['jobDescription'];
        
        div.appendChild(parag);
    //Lo unisco al blocco principale
    divJob.appendChild(div);

        //creo il div dei like
        div = document.createElement('div');
        div.classList.add("job-like");
        let span = document.createElement('span');
        em = document.createElement('em');
        em.textContent=aJob['jobNLikes'];
        span.textContent=" Like!";
        div.appendChild(em);
        div.appendChild(span);
        div.addEventListener('click',checkLike);
    //Lo unisco al blocco principale
    divJob.appendChild(div);

    //Inserisco il nuovo job nella sezione
    sectionJobList.appendChild(divJob);
}
function onJobJson(response){
    if (response['result']===0 || response['result']===3){
        console.log("Errore: "+response['message']);
    } else{
        //console.log(response['message']);
        for (job of response['jobList']){
            //console.log("Id: "+job['jobId']);
            //console.log("Titolo: "+job['jobTitle']);
            //console.log("Cliente: "+job['jobCustomer']);
            //console.log("Immagine: "+job['jobImage']);
            createHtmlJobElement(job);
        }
        checkLike();
    }
}

function onJobResponse(response){
    return response.json();
}
//funzione che restituisce l'array dei job sia quando si clicca sulla ricerca che all'avvio
function retriveJob(event){
    const formdata = new FormData();
    const sectionJobList = document.querySelector('#job-list');
    sectionJobList.innerHTML="";
    if (event!==undefined){
        //per la ricerca
        formdata.append("type","3");
        formdata.append("keyRicerca",document.querySelector('#divRicerca #keyRicerca').value);
        event.preventDefault();
    } else {
        formdata.append("type","0");
        //quando chiamato per avere l'elenco completo
        console.log("Richiedi tutti");
    }
    fetch("classes/retriveJob.php",{method:"post",body:formdata}).then(onJobResponse).then(onJobJson);
}

retriveJob();


/* Funzionamento pulsante chiusura overlay */
function closeOverlay(event) {
    const layerOverlay = document.querySelector('#overlay');
    const overlayContent = document.querySelector('#overlay .content');
    overlayContent.innerHTML = '';
    document.body.classList.remove('no-scroll');
    layerOverlay.classList.add('hidden');
    event.stopPropagation();
}
const btnCloseOverlay = document.querySelector('#overlay .close');
btnCloseOverlay.addEventListener('click', closeOverlay);


/* -------------------------------------------------------------
GESTIONE TOOL RICERCA JOB
Posso inserire delle parole chiave per ricercare dei job.
se non inserisco nessuna parola mi verranno restituiti tutti i 
lavori 
------------------------------------------------------------- */

function checkKeyRicerca(event){
    const input=event.currentTarget;
    const valore=input.value;
    if (!/^[0-9a-zA-Z -]{0,255}$/.test(valore)){
        input.classList.add('error');
        document.querySelector('#divRicerca #btnRicerca').disabled=true;
    } else {
        input.classList.remove('error');
        document.querySelector('#divRicerca #btnRicerca').disabled=false;
    }
}

document.querySelector('#divRicerca input').addEventListener('change', checkKeyRicerca);
document.querySelector('#divRicerca form').addEventListener('submit', retriveJob);