/**
 * Created by tlassus on 17/02/2017.
 */

var premierChargement = 1;

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('#line-chart-1week-panel').hide();
    $('.demande-filter-selected').click();

    $('#easypiechart-blue').easyPieChart({
        scaleColor: false,
        barColor: '#30a5ff'
    });

});


$('.demande-filter.clickable').click(function(){

    if( !$(this).hasClass('demande-filter-selected') || premierChargement) {

        $('.demande-filter-selected').addClass('demande-filter');
        $('.demande-filter-selected').addClass('clickable');
        $('.demande-filter-selected').removeClass('demande-filter-selected');

        $(this).removeClass('demande-filter');
        $(this).removeClass('clickable');
        $(this).addClass('demande-filter-selected');

        if (!premierChargement) {

            $('#ratioHomeTopPanel').html($('#genericLoader').html());
            $('#traiteesHomeTopPanel').html($('#genericLoader').html());
            $('#enCoursHomeTopPanel').html($('#genericLoader').html());

            var currentUrl = window.location.href;
            var URLsplited = currentUrl.split("_");
            var dString = URLsplited[URLsplited.length - 1];
            var dStringVeille = '';

            if (dString.length != 10) {
                var d = new Date();
                dString = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
                d.setDate(d.getDate() - 1);
                dStringVeille = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
            }

            var id = $(this).attr('id');

            $.ajax({
                url: Routing.generate('home_top_panel_ratio'),
                data: {
                    date: dStringVeille,
                    idPartenaire: id
                },
                success: function (data) {
                    $('#ratioHomeTopPanel').html(data);
                    $('.easypiechart').easyPieChart({
                        scaleColor: false,
                        barColor: '#30a5ff'
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $.ajax({
                url: Routing.generate('home_top_panel_traitees'),
                data: {
                    date: dStringVeille,
                    idPartenaire: id
                },
                success: function (data) {
                    $('#traiteesHomeTopPanel').html(data);
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $.ajax({
                url: Routing.generate('home_top_panel_encours'),
                data: {
                    date: dString,
                    idPartenaire: id
                },
                success: function (data) {
                    $('#enCoursHomeTopPanel').html(data);
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

        } else {
            premierChargement = 0;
        }
    }

});







