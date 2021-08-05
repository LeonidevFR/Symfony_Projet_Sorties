window.onload = () => {
    const apiUrl = 'https://geo.api.gouv.fr/communes';
    const Http = new XMLHttpRequest;
    var data;
    document.getElementById('ville-input').addEventListener('keyup', () => {
        const villeSelect = document.getElementById('ville-select');
        const codePostalSelect = document.getElementById('codePostal-select')
        const villeInput = document.getElementById('ville-input').value
        while(villeSelect.firstChild) {
            villeSelect.removeChild(villeSelect.lastChild);
        };
        while(codePostalSelect.firstChild) {
            codePostalSelect.removeChild(codePostalSelect.lastChild);
        };
        codePostalSelect.setAttribute('disabled', true);
        let url = '';
        if(villeInput.length >= 3) {
            let i = 0;
            url = 'https://geo.api.gouv.fr/communes/?nom=' + villeInput + '&fields=codesPostaux';
            Http.open("GET", url);
            Http.responseType = 'json';
            Http.send();
            Http.onreadystatechange = () => {
                data = Http.response;
                for (const key in data) {
                    let existe = false;
                    const nomVille = data[key]['nom'];
                    const children = villeSelect.childNodes;
                    for(let i = 0; i < children.length; i++){
                        if(children[i].textContent == nomVille)
                            existe = true;
                    }
                    if(existe == false)
                        option = optionCreation(nomVille, villeSelect);
                    const codesPostaux = data[key]['codesPostaux'];
                    (() => {
                        if(villeInput == nomVille) {
                            for (let i = 0; i < codesPostaux.length; i++) {
                                codePostalSelect.removeAttribute('disabled', false);
                                const codePostal = data[key]['codesPostaux'][i]
                                optionCreation(codePostal, codePostalSelect)
                            }
                        }
                    })();
                    i++;
                    if (i >= 10)
                        break;
                }
            }
        }
    });

    document.getElementById('ville-input').addEventListener('change', () => {
        document.getElementById('ville-input').blur()
    });
    function optionCreation(value, parent) {
        var option = document.createElement('option');
        option.setAttribute('id', value);
        option.setAttribute('title', value);
        option.value = value;
        option.innerHTML = value;
        parent.appendChild(option);
        return option;
    };
}
