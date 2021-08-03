window.onload = () => {
    const apiUrl = 'https://geo.api.gouv.fr/communes';
    const Http = new XMLHttpRequest;
    document.getElementById('ville-input').addEventListener('keyup', () => {
        let villeSelect = document.getElementById('ville-select');
        const villeInput = document.getElementById('ville-input').value
        while(villeSelect.firstChild){
            villeSelect.removeChild(villeSelect.lastChild)
        }
        let url = '';
        if(villeInput.length >= 3){
            url = 'https://geo.api.gouv.fr/communes/?nom=' + villeInput + '&fields=nom';
            Http.open("GET", url);
            Http.responseType = 'json';
            Http.send();
            let i = 0;
            Http.onreadystatechange = () => {
                const data = Http.response;
                for (const key in data) {
                    const nomVille = data[key]['nom'];
                    if(document.getElementById(nomVille) == null){
                        const option = document.createElement('option');
                        option.setAttribute('id', nomVille);
                        option.value = nomVille;
                        villeSelect.appendChild(option);
                        i++;
                    }
                    if (i >= 10)
                        break;
                }
            }
        }
    })
}