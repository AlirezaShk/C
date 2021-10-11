const Archive = new BaseArchive($("#my-script").data('confirm'));
const debounce = function (func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

$("#search-bar ul.filters li").click(function (e) {
    let li = this;
    Archive.Crawler.toggleSearchField(li);
});

$("#search-bar ul.sorters li").click(function (e) {
    let li = this;
    Archive.Crawler.toggleSortField(li);
});

$("#submit-search").click(function (e) {
    let data = [];
    let filterVal = $("#new-filter").val().trim();
    let filterTypes = $("#search-bar ul.filters span.active");
    for (let i = 0; i < filterTypes.length; i++) {
        data.push({
            'type': $(filterTypes[i]).parent().data('type'),
            'val' : filterVal,
        });
    }
    Archive.Crawler.applyFilter(data);
});

$("#submit-search").click(function (e) {
    let data = [];
    let filterVal = $("#new-filter").val().trim();
    let filterTypes = $("#search-bar ul.filters span.active");
    for (let i = 0; i < filterTypes.length; i++) {
        data.push({
            'type': $(filterTypes[i]).parent().data('type'),
            'val' : filterVal,
        });
    }
    Archive.Crawler.applyFilter(data);
});

Archive.Crawler.configureCurrentFilters()

