const type = document.getElementById('type');
const user_form = document.getElementById('user_form');
const extra = document.getElementById('extra');

function renderOptions(url){
    fetch(url)
    .then( response => response.json() )
    .then( options  => {
        extra.innerHTML = '';
        for(name in options){
            let input = document.createElement('input', name);
            let cut = document.createElement('br');
            input.name = name;
            input.placeholder = name;
            input.type = options[name];
            extra.insertBefore(input, undefined);
            extra.insertBefore(cut, undefined);
        }
        let send_form = document.createElement('input');
        send_form.type = 'submit';
        send_form.value = "Register";
        extra.insertBefore(send_form, undefined);
    });
}


type.addEventListener('change', ()=>{
    if(type.value === "Employee")
        renderOptions('/JobsNow/register/employee');
    else if(type.value === "Company")
        renderOptions('/JobsNow/register/company');
});
