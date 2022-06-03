
function checkForm(){
    const button=document.querySelector('#btnSubmit');
    const esiti=document.querySelectorAll('#divEditJob span');
    let formOk=true;
    button.disabled=false;
    for(let esito of esiti){
        if (esito.className !== "hidden"){
            button.disabled=true;
            formOk=false;
        }
    }    
    return formOk;
}

function checkJobTitle(event){
    const input=event.currentTarget;
    const valore=input.value;
    if (!/^[a-zA-Z '']{1,255}$/.test(valore)){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    } else {
        input.classList.remove('error');
        input.parentNode.parentNode.querySelector('span').classList.add('hidden');
    }
    checkForm();
}
function checkJobCustomer(event){
    const input=event.currentTarget;
    const valore=input.value;
    if (!/^[a-zA-Z -.'/]{1,255}$/.test(valore)){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    } else {
        input.classList.remove('error');
        input.parentNode.parentNode.querySelector('span').classList.add('hidden');
    }
    checkForm();
}
function checkJobDevice(event){
    const input=event.currentTarget;
    const valore=input.value;
    if (!/^[0-9a-zA-Z -.'/]{1,255}$/.test(valore)){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    } else {
        input.classList.remove('error');
        input.parentNode.parentNode.querySelector('span').classList.add('hidden');
    }
    checkForm();
}
function checkJobEndingYear(event){
    const input=event.currentTarget;
    const valore=input.value;
    if (!/^[0-9]{0,4}$/.test(valore)){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    } else {
        input.classList.remove('error');
        input.parentNode.parentNode.querySelector('span').classList.add('hidden');
    }
    checkForm();
}
function checkJobDescription(event){
    const input=event.currentTarget;
    const valore=input.value;
    if ((valore.length>=1) && (valore.length<=1000)){
        input.classList.remove('error');
        input.parentNode.parentNode.querySelector('span').classList.add('hidden');
    } else {
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    }
    checkForm();
}
function checkJobKeywords(event){
    const input=event.currentTarget;
    const valore=input.value;
    if (!/^[0-9a-zA-Z -]{0,255}$/.test(valore)){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    } else {
        input.classList.remove('error');
        input.parentNode.parentNode.querySelector('span').classList.add('hidden');
    }
    checkForm();
}
function checkJobGps(event){
    const input=event.currentTarget;
    const valore=input.value;
    if (!/^[0-9.,]{1,20}$/.test(valore)){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    } else {
        input.classList.remove('error');
        input.parentNode.parentNode.querySelector('span').classList.add('hidden');
    }
    checkForm();
}

function checkImage(event){
    const input=event.currentTarget;
    const valore=input.value;
    if (!/^[0-9a-zA-Z.-:'/]{0,255}$/.test(valore)){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    } else {
        input.classList.remove('error');
        input.parentNode.parentNode.querySelector('span').classList.add('hidden');
    }
    checkForm();
}

function onJSON(response){
    console.log("json " + response);
    let jobId;
    const lblEsito=document.querySelector('.jobSubmit strong');
    let dettaglioEsito="";
    lblEsito.classList.remove("hidden");
    lblEsito.classList.add('error');
    if (response['result']===2){
        document.querySelector('.jobTitle input').value="";
        document.querySelector('.jobCustomer input').value="";
        document.querySelector('.jobDevice input').value="";
        document.querySelector('.jobEndingYear input').value="";
        document.querySelector('.jobDescription input').value="";
        document.querySelector('.jobLat input').value="";
        document.querySelector('.jobLong input').value="";
        document.querySelector('.jobKeywords input').value="";
        document.querySelector('.jobHasVideo input').checked=false;
        document.querySelector('.jobEnded input').checked=false;
        jobId=response['jobId'];
        lblEsito.classList.remove("error");
        dettaglioEsito= "Nuovo Id: " + jobId;
    } else if(response['result']===3){
        let nErrore=0;
        for (let errore of response['errori']){
            nErrore++;
            dettaglioEsito= dettaglioEsito + "Errore nr." + nErrore + ": "+errore+ "<br/>";
        }
    }
    
    lblEsito.innerHTML="Esito: "+response['message']+ ".<br/> "+dettaglioEsito;
}
function onResponse(response){
    console.log(response);
    if (!response.ok){
        return null;
    }
    return response.json();
}
function editJob(event){
    event.preventDefault();
    let input =document.querySelector('.jobTitle input');
    if (input.value===""){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    }
    input =document.querySelector('.jobCustomer input');
    if (input.value===""){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    }
    input =document.querySelector('.jobDevice input');
    if (input.value===""){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    }
    input =document.querySelector('.jobDescription input');
    if (input.value===""){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    }
    input =document.querySelector('.jobLat input');
    if (input.value===""){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    }
    input =document.querySelector('.jobLong input');
    if (input.value===""){
        input.classList.add('error');
        input.parentNode.parentNode.querySelector('span').classList.remove('hidden');
    }
    if(checkForm()){
        const formdata = new FormData();
        const btnSubmit=document.getElementById("btnSubmit");
        if (btnSubmit.value==="Modifica"){
            formdata.append("type","1");
            formdata.append("jobId",btnSubmit.dataset.job_id);
        } else {
            formdata.append("type","2");
        }
        formdata.append("jobTitle",document.querySelector('.jobTitle input').value);
        formdata.append("jobCustomer",document.querySelector('.jobCustomer input').value);
        formdata.append("jobDevice",document.querySelector('.jobDevice input').value);
        formdata.append("jobEndingYear",document.querySelector('.jobEndingYear input').value);
        formdata.append("jobDescription",document.querySelector('.jobDescription input').value);
        formdata.append("jobLat",document.querySelector('.jobLat input').value);
        formdata.append("jobLong",document.querySelector('.jobLong input').value);
        formdata.append("jobKeywords",document.querySelector('.jobKeywords input').value);
        formdata.append("jobHasVideo",document.querySelector('.jobHasVideo input').checked);
        formdata.append("jobEnded",document.querySelector('.jobEnded input').checked);
        formdata.append("jobImage",document.querySelector('.jobImage input').value);
        fetch("classes/retriveJob.php",{method:"post",body:formdata}).then(onResponse).then(onJSON);
    }
}

document.querySelector('.jobTitle input').addEventListener('blur', checkJobTitle);
document.querySelector('.jobCustomer input').addEventListener('blur', checkJobCustomer);
document.querySelector('.jobDevice input').addEventListener('blur', checkJobDevice);
document.querySelector('.jobEndingYear input').addEventListener('blur', checkJobEndingYear);
document.querySelector('.jobDescription input').addEventListener('blur', checkJobDescription);
document.querySelector('.jobLat input').addEventListener('blur', checkJobGps);
document.querySelector('.jobLong input').addEventListener('blur', checkJobGps);
document.querySelector('.jobKeywords input').addEventListener('blur', checkJobKeywords);
document.querySelector('.jobHasVideo input').addEventListener('change', checkForm);
document.querySelector('.jobEnded input').addEventListener('change', checkForm);
document.querySelector('.jobImage input').addEventListener('blur', checkImage);
document.querySelector('#divEditJob form').addEventListener('submit', editJob);