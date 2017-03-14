// var _throttleTimer = null;
// var _throttleDelay = 100;
// var $window = $(window);
// var $document = $(document);
//
// $document.ready(function () {
//     $window
//         .off('scroll', ScrollHandler)
//         .on('scroll', ScrollHandler);
//
// });
//
// function ScrollHandler(e) {
//     //throttle event:
//     clearTimeout(_throttleTimer);
//     _throttleTimer = setTimeout(function () {
//         console.log('scroll');
//
//         //do work
//         if ($window.scrollTop() + $window.height() > $document.height() - 50) {
//             if($('div.tab-content div.tab-pane.active div.table-responsive #nomore').length == 0)
//                 lastEvents();
//         }
//
//     }, _throttleDelay);
// }
// var isLoading = false;
// function lastEvents() {
//     if (!isLoading) {
//         isLoading = true;
//         $('div.tab-content div.active #loaderdemande').show();
//
//         var table = $('.tab-pane.fade.in.active').attr('id');
//         var nb = $('div.tab-content div.active .table#'+table+' tr').length;
//         var debut = $('#between_date_search_debut').attr('value');
//         var fin = $('#between_date_search_fin').attr('value');
//         var url = Routing.generate('demande_index',{} );
//
//         $.ajax({
//             url: url,
//             data: {
//                 offset: nb,
//                 table: table,
//                 fin : fin,
//                 debut: debut
//             },
//             success: function (data) {
//                 if(data.trim() != '')
//                 {
//                     isLoading = false;
//                     $('div.tab-content div.active #loaderdemande').hide();
//                     $('.tab-content div#'+table+' table#'+table).DataTable().rows.add(
//                         data
//                     )
//                     //$('.tab-content div#'+table+' table#'+table+' tbody').append(data);
//                 }
//                 else{
//                     isLoading = false;
//                     $('div.tab-content div.active #loaderdemande').hide();
//                     $('.tab-content div#'+table+' div.table-responsive').append("<div id='nomore'></div>");
//                 }
//
//             },
//             error : function()
//             {
//             }
//         });
//     }
// }



