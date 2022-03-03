const algoliaClient = algoliasearch(
    '2BNKFRXSL7',
    '3f16cfd9712309205322ece7ae637084'
);


// const searchClient = {
//     ...algoliaClient,
//     search(requests) {
//         if (requests.every(({ params }) => !params.query)) {
//             return Promise.resolve({
//                 results: requests.map(() => ({
//                     hits: [],
//                     nbHits: 0,
//                     nbPages: 0,
//                 })),
//             });
//         }
//         console.log(requests);
//         return algoliaClient.search(requests);
//     },
// };
// const search = instantsearch({
//     indexName: 'varken',
//     searchClient,
//     routing: true,
//     aroundLatLng: '23.382338042992096, 85.3364097475674',
//     onStateChange({ uiState, setUiState }) {

//         setUiState(uiState);
//     },
//     searchFunction: function (helper) {
//         if (helper.state.query === '') {
//             return;
//         }

//         helper.search();
//     },
// });
// let search_term = '';
// const renderHits = (renderOptions, isFirstRender) => {
//     const { hits, widgetParams } = renderOptions;
//     console.log(hits.length);
//     if (hits.length === 0) {
//         widgetParams.container.innerHTML = `<div class='no_content_err-container'>
// <img src='./css/svg/error.svg' alt='' class='no_content_err-img'>
// <span class='no_content_err-msg'>We didn't find the product<br>Its perfect time for pictorial order..☺️<span>
// </div>`;
//     } else {
//         widgetParams.container.innerHTML = `
// ${hits
//                 .map(
//                     item => {
//                         let { product_image: img,
//                             product_name: p_name,
//                             product_price: p_price,
//                             product_id: p_id,
//                             product_publisher_id: p_p_id,
//                             product_size: p_size,
//                             product_color: p_color } = item;
//                         let p_img = img.split(' ')[0];
//                         return `<div class='main-overview-container'>
// <div class='overview_container'>
// <a href='./product.php?i=${p_id}' class='overview-img_container'>
// <img class='overview-img' src='../../uploaded_files/${p_img}' alt='img'loading='lazy'>
// </a>
// <span class='overview-details-list '>
// <a href='./product.php?i=${p_id}' class='overview-detail name repeated_markup-check' data-name='${p_name}'>${p_name}</a>
// <a href='./product.php?i=${p_id}' class='overview-detail price'>Rs. ${p_price}</a>
// <div class='btn-container'>
// <span class='overview-detail btn-1' onclick="open_quickii_modal('${p_img}','${p_name}','${p_price}','${p_color}','${p_size}','${p_p_id}','${p_id}')">Quickii..</span>
// </div>
// </span>
// </div>
// </div>`})
//                 .join('')}`;
//     };
//     // manage_markups();
// };
// const customHits = instantsearch.connectors.connectHits(renderHits);
// search.addWidgets([
//     instantsearch.widgets.searchBox({
//         container: '#search_input-container',
//         placeholder: 'Search...',
//         autofocus: false,
//         searchAsYouType: false,
//         showReset: false,
//         showSubmit: true,
//         showLoadingIndicator: false,
//     }),
//     customHits({
//         container: document.querySelector('#main-content'),
//     }),
// ]);
// search.start();
// document.querySelector('#search-btn').addEventListener("click", function () {
//     jQuery(function () {
//         jQuery('.ais-SearchBox-submit').click();
//     });
// });
// load_tab();
// document.querySelector('.ais-SearchBox-input').setAttribute('type', 'text');

function search(query) {
    let searched_prd_markup = ``;
    algoliaClient.initIndex('varken_products').search(query, {
        aroundLatLng: '23.381809993197447, 85.32847040217709'
    }).then(({ hits }) => {
        document.querySelector('main').innerHTML = searched_prd_markup;
        hits.forEach(prd_dtl => {
            if ($(`[data-markup_check="${prd_dtl.product_name}:${prd_dtl.product_price}"]`)[0]) {
            } else {
                searched_prd_markup = `<div class='main-overview-container' data-markup_check='${prd_dtl.product_name}:${prd_dtl.product_price}'>
                <div class='overview_container'>
                <a href='./product.php?i=${prd_dtl.product_id}' class='overview-img_container'>
                <img class='overview-img' src='../../uploaded_files/${(prd_dtl.product_image).split(" ")[0]}' alt='img' loading='lazy'>
                </a>
                <span class='overview-details-list '>
                <a href='./product.php?i=${prd_dtl.product_id}' class='overview-detail name repeated_markup-check' data-name='${prd_dtl.product_name}:${prd_dtl.product_price}'>${prd_dtl.product_name}</a>
                <a href='./product.php?i=${prd_dtl.product_id}' class='overview-detail price'>Rs. ${prd_dtl.product_price}</a>
                <div class='btn-container'>
                <span class='overview-detail btn-1' onclick="open_quickii_modal('${prd_dtl.product_image.split(" ")[0]}','${prd_dtl.product_name}','${prd_dtl.product_price}','${prd_dtl.prodcut_color}','${prd_dtl.product_size}','${prd_dtl.seller_id}','${prd_dtl.product_id}')">Quickii..</span>
                </div>
                </span>
                </div>
                </div>`;
                    document.querySelector('main').insertAdjacentHTML("beforeend", searched_prd_markup)
            }
        });
        if (!window.location.search.includes(`?search=${query}`)) {
            if(window.location.search.includes(`?c_id=`)||window.location.search.includes(`?p_id=`)){
                window.location.href= `/?search=${query}`
            }else{
                history.pushState("", "", `?search=${query}`);
            }
        };
    });
};
function run_srch_func() {
    if (window.location.search.includes("?search")) {
        const srch_dtl_arr = window.location.search.split("=");
        if (srch_dtl_arr[1] !== '') {
            search(srch_dtl_arr[1]);
        } else {
        }
    }
}
run_srch_func();
document.getElementById("search-field").addEventListener("keydown", function (e) {
    if (e.code === 'Enter') {
        search(this.value)
    }
});
window.addEventListener("popstate", function () {
    if (window.location.search.includes("?search")) {
        run_srch_func();
    }
    document
        .getElementById("menu_container")
        .classList.remove("menu_container-display");
});
