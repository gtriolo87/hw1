function openDivLogin(event) {
    const divLogin = document.querySelector('#loginWindow');
    if (divLogin.className === "hidden") {
        divLogin.classList.remove("hidden");
    } else {
        divLogin.classList.add("hidden");
    }
}
/* alla prima esecuzione definisco il click degli item Men� */
const btnLogin = document.querySelector('#btnLogin');
btnLogin.addEventListener('click', openDivLogin);