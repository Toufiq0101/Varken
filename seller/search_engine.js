// const algoliaClient = algoliasearch(
// '58PJRFN4K1',
// 'b8911656c379f47c8991d03ab3d2f816'
// );
const algoliaClient = algoliasearch(
'2BNKFRXSL7',
'3f16cfd9712309205322ece7ae637084'
);
const searchClient = {
...algoliaClient,
search(requests) {
if (requests.every(({ params }) => !params.query)) {
return Promise.resolve({
results: requests.map(() => ({
hits: [],
nbHits: 0,
nbPages: 0,
})),
});
}
return algoliaClient.search(requests);
},
};
const search = instantsearch({
indexName: 'halka_products',
searchClient,
routing: true,
onStateChange({ uiState, setUiState }) {

setUiState(uiState);
},
searchFunction: function(helper) {
if (helper.state.query === '') {
return;
}

helper.search();
},
});
let search_term = '';
const renderHits = (renderOptions, isFirstRender) => {
const { hits, widgetParams } = renderOptions;
console.log(hits.length);
if(hits.length===0){
widgetParams.container.innerHTML = `<div class='no_content_err-container'>
<img src='./css/svg/error.svg' alt='' class='no_content_err-img'>
<span class='no_content_err-msg'>No Product Found</span>
</div>`;
}else{
widgetParams.container.innerHTML = `
${hits
.map(
item =>{
let {product_image: img,
product_name: p_name,
product_price: p_price,
product_id :p_id,
product_publisher_id:p_p_id,
product_size:p_size,
product_color:p_color,
product_availability:p_avail,
product__date:p_date,
} = item;
let p_img = img.split(' ')[0];
let markup = ``;
    markup += `<div class='product_overview_container'>`;
    if (p_avail === 'Available') {
        markup += `<div class='product_overview product-availabel'>`;
    } else {
        if (p_avail === 'Out of Stock') {
            markup += `<div class='product_overview product-unavailabel'>`
        } else {
            markup += `<div class='product_overview product-comming'>`
        }
    }
    markup += `<a href='./product.php?edit=${p_id}' class='product_image'><img class='product-image'
                        src='../../uploaded_files/${p_img}' loading='lazy'></a><a
                    href='./product.php?edit=${p_id}' class='product_details '><span
                        class='product-name'>${p_name}</span><span class='product-price'>Rs.
                        ${p_price}</span><span class='product-date'>Date:-${p_date}</span></a><input
                    class='product-checkbox' type='checkbox' name='del_p_id' value='${p_id}'>
            </div>
        </div>`;
        return markup;
    
})
.join('')}`;}
};
const customHits = instantsearch.connectors.connectHits(renderHits);
search.addWidgets([
instantsearch.widgets.searchBox({
container: '#search_input-container',
placeholder: 'Search...',
autofocus: false,
searchAsYouType: false,
showReset: false,
showSubmit: true,
showLoadingIndicator: false,
}),
customHits({
container: document.querySelector('#main-content'),
}),
]);
search.start();
document.querySelector('#search-btn').addEventListener("click",function () {
jQuery(function(){
jQuery('.ais-SearchBox-submit').click();
 });
});
tab();
document.querySelector('.ais-SearchBox-input').setAttribute('type', 'text');