(()=>{
    let search = document.getElementById('search');
    let input_search = [];
    search.addEventListener('keyup', function (){
        input_search = {search: search.value};
        fetch('/JobsNow/Search', {
            method: 'POST', 
            body: JSON.stringify(input_search), 
            headers:{'Content-Type': 'application/json'}
        })
        .then(responseJson => responseJson.json()).then(res => renderPartialResult(res));
    });
})();

function renderPartialResult(search_results){
    let div_father = document.getElementById('partial_result');

    while (div_father.firstChild) {
        div_father.removeChild(div_father.firstChild);
    }

    document.getElementById('menu').style.display = "inline";
    for(let search_result in search_results){
            let label_id = search_results[search_result]['id'];
            let label = search_results[search_result]['name'];
            

            let a_tag_result = document.createElement("a");
            a_tag_result.innerHTML = label;
            a_tag_result.className = 'dropdown-item';
            a_tag_result.href = '/jobsnow/profile/general?id='+label_id;
            div_father.insertBefore(a_tag_result, undefined);
        
    }
}