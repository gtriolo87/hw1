
function onJsonEdit(response){
    const risultato = document.getElementById("esitoModifica");
    risultato.classList.remove("hidden");
    risultato.textContent=response;
    searchUser();
}

function responseFetch(response){
    return response.json();
}

function aggiornaProfilo(event){
    event.preventDefault();
    const user_id=event.currentTarget.dataset.user_id;
    const group_id=document.querySelector('select[data-user_id="'+user_id+'"]').selectedIndex;

    fetch("classes/editUser.php?user_id="+user_id+"&group_id="+group_id).then(responseFetch).then(onJsonEdit);
}

function onJsonUser(json){
    let numUser=0;
    const lista = document.querySelector("tbody");
    lista.innerHTML = '';
    for(user of json)
    {
        const tr = document.createElement("tr")
        let td = document.createElement("td");
        td.innerHTML=user.user_id;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML=user.username;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML=user.name;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML=user.surname;
        tr.appendChild(td);
        td = document.createElement("td");
        td.innerHTML=user.email;
        tr.appendChild(td);
        td = document.createElement("td");
        let select = document.createElement("select");
        let option = document.createElement("option");
        option.text = "Nascosto";
        option.value=0;
        option.hidden=1;
        select.add(option);
        option = document.createElement("option");
        option.text = "Visitatore";
        option.value=1;
        select.add(option);
        option = document.createElement("option");
        option.text = "Amministratore";
        option.value=2;
        select.add(option);
        option = document.createElement("option");
        option.text = "Manager";
        option.value=3;
        select.add(option);
        option = document.createElement("option");
        option.text = "Operatore";
        option.value=4;
        select.add(option);
        select.selectedIndex=user.group_id;
        select.dataset.user_id=user.user_id;
        td.appendChild(select);
        tr.appendChild(td);
        td = document.createElement("td");
        const update = document.createElement("a");
        update.href="#";
        update.dataset.user_id = user.user_id;
        update.textContent = "Aggiorna";
        update.addEventListener("click", aggiornaProfilo);
        td.innerHTML='';
        td.appendChild(update);
        tr.appendChild(td);
        lista.appendChild(tr);
        numUser++;
    }
    if (numUser===0){
        const tr = document.createElement("tr")
        const td = document.createElement("td");
        td.textContent ='Nessun utente presente';
        tr.appendChild(td);
        lista.appendChild(tr);
    }
}

function searchUser(event){
    let filter="";
    let groupSelected;
    if (event!==undefined){
        event.preventDefault();

        const risultato = document.getElementById("esitoModifica");
        risultato.classList.add("hidden");
        risultato.textContent="";

        const option=document.querySelector('#formSearch select');
        groupSelected=option.value;
        if(groupSelected!=="0"){
            filter="?filterProfile="+groupSelected;
        }
    }
    fetch("classes/retriveUser.php"+filter).then(responseFetch).then(onJsonUser);
}

searchUser();

document.querySelector('#formSearch').addEventListener('submit',searchUser);