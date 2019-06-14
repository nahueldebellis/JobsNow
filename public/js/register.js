const type = document.getElementById('type');
function renderOptions(url){
    fetch(url)
    .then( response => response.json() )
    .then( options  => console.table(options));
}

type.addEventListener('change', ()=>{
    if(type.value === "Employee")
        renderOptions('JobsNow/register/employee');
    else
        renderOptions('JobsNow/register/company');
});
