function dragElement(elmnt, pos)
{
    jQuery(function ($) {
        let mainSec = document.getElementById("advanced-search_filters").getElementsByClassName("draggable-filter")[0];
        let ibar = [];
        ibar[0] = $("#advanced-search_filters").find('.invalid-bar:eq(0)')[0];
        ibar[1] = $("#advanced-search_filters").find('.invalid-bar:eq(1)')[0];
        let pos1 = 0, pos2 = 0;

        $(elmnt).mousedown(dragMouseDown);

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos2 = e.clientX;
            $(document).mouseup(closeDragElement);
            // call a function whenever the cursor moves:
            $(document).mousemove(elementDrag);
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos2 - e.clientX;
            pos2 = e.clientX;
            // set the element's new position:
            let diffX = - mainSec.getBoundingClientRect().left + e.clientX - 10;
            if(pos === 0){
                if ( diffX < (max - 10) && diffX > (-10) ) {
                    elmnt.style.left = diffX + "px";
                    min = diffX;
                    ibar[0].style.width = (diffX+10 - pos1) + "px";
                    calcThePeriod(ibar[0], ibar[1], mainSec);
                }
            } else {
                if ( diffX > (min + 10) && diffX < (mainSec.getBoundingClientRect().width - 10) ) {
                    elmnt.style.left = diffX + "px";
                    elmnt.style.right = "unset";
                    max = diffX;
                    ibar[1].style.width = ($(mainSec).width()-diffX-10 - pos1) + "px";
                    calcThePeriod(ibar[0], ibar[1], mainSec);
                }
            }
            console.log(min, max);
        }

        function closeDragElement(e) {
            /* stop moving when mouse button is released:*/
            $(document).unbind("mouseup").unbind("mousemove");
        }
    });
}
function putFigure(Inp)
{
    let mainSec = document.getElementById("advanced-search_filters").getElementsByClassName("draggable-filter")[0];
    let D = document.getElementById("advanced-search_filters").getElementsByClassName("draggable-filter")[0].getElementsByClassName("draggable");
    let minInp = $("#advanced-search_filters").find(".draggable-period").find("input:eq(0)")[0];
    let min = parseInt($(minInp).val());
    let maxInp = $("#advanced-search_filters").find(".draggable-period").find("input:eq(1)")[0];
    let max = parseInt($(maxInp).val());
    let L = $("#advanced-search_filters").find(".draggable-limits:eq(0)").find("span");
    let topLimit = parseInt(L[4].innerText);
    let botLimit = parseInt(L[0].innerText);
    let wholeMax = $(mainSec).width();
    jQuery(function ($) {
        let ibar = [];
        if (min < botLimit) {
            min = botLimit;
            $(Inp).val(min);
        }
        if (max > topLimit) {
            max = topLimit;
            $(Inp).val(max);
        }
        if((max - min) <= 0){
            if ($(Inp).attr("name") === "maxYear") {
                max = min + 1;
                $(Inp).val(max);
            } else {
                min = max - 1;
                $(Inp).val(min);
            }
        }
        ibar[0] = $("#advanced-search_filters").find('.invalid-bar:eq(0)')[0];
        ibar[0].style.width = ( (min - botLimit) / (topLimit - botLimit) ) * wholeMax + "px";
        D[0].style.left = ( (min - botLimit) / (topLimit - botLimit) ) * wholeMax - 10 + "px";

        ibar[1] = $("#advanced-search_filters").find('.invalid-bar:eq(1)')[0];
        ibar[1].style.width = ( (topLimit - max) / (topLimit - botLimit) ) * wholeMax + "px";
        D[1].style.right = ( (topLimit - max) / (topLimit - botLimit) ) * wholeMax - 10 + "px";
    });
}
function calcThePeriod(minBar, maxBar, wholeBar)
{
    let L = $("#advanced-search_filters").find(".draggable-limits:eq(0)").find("span");
    let topLimit = parseInt(L[4].innerText);
    let botLimit = parseInt(L[0].innerText);
    let wholeMax = $(wholeBar).width();
    let min = $(minBar).width();
    let max = $(maxBar).width();
    let period = [];
    period[0] = min/wholeMax;
    period[1] = 1-max/wholeMax;
    period[0] = period[0] * (topLimit - botLimit) + botLimit;
    period[1] = period[1] * (topLimit - botLimit) + botLimit;
    period[0] = Math.round(period[0]);
    period[1] = Math.round(period[1]);
    $("#advanced-search_filters").find(".draggable-period").find("input[name='minYear']").val(period[0]);
    $("#advanced-search_filters").find(".draggable-period").find("input[name='maxYear']").val(period[1]);
}
function durFormatter(dur_inp)
{
    let val = $(dur_inp).val();
    if (val.length === 1) {
        val = "0" + val;
        $(dur_inp).val(val);
    }
    val = parseInt(val);
    let [min, max] = [parseInt($(dur_inp).attr("min")), parseInt($(dur_inp).attr("max"))];
    console.log(val, min, max)
    if (val <= min) $(dur_inp).val(max - 1);
    else if (val >= max) $(dur_inp).val("00");
}
function initializeReleaseYearFilter(minL, maxL)
{
    $(".filters-resultType-each").each(function (){
        $(this).click(function () {
            changeSearchMode(this);
        });
    });

    $("#advanced-search_filters .dur-each input").each(function (){
        let that = this;
        $(this).bind("input",function (){
            durFormatter(that);
        });
    });

    dragElement($("#advanced-search_filters").find(".draggable:eq(0)")[0], 0);
    dragElement($("#advanced-search_filters").find(".draggable:eq(1)")[0], 1);
    jQuery(function ($) {
        let L = $("#advanced-search_filters").find(".draggable-limits:eq(0)").find("span");
        let I = $("#advanced-search_filters").find(".draggable-period").find("input");
        // minL -= 0.20;
        maxL += 0.50;
        let each_chunk = (maxL - minL)/4;
        for(let i = 0; i < L.length; i++) {
            $(L[i]).text(parseInt(minL + each_chunk * i));
        }
        $(I[0]).val(parseInt($(L[0]).text()));
        $(I[1]).val(parseInt($(L[4]).text()));
    });
}
function changeOperator(elmnt)
{
    let that = elmnt;
    let op = $(that).html();
    // if (op === "و") op = ") یا (";
    // else if (op === ") یا (") op = "و";
    // else if (op === "and") op = ") or (";
    // else if (op === ") or (") op = "and";
    if (op === "و") op = "یا";
    else if (op === "یا") op = "و";
    else if (op === "and") op = "or";
    else if (op === "or") op = "and";
    $(that).html(op);
    if ($(that).hasClass("op-or")) $(that).removeClass("op-or").addClass("op-and");
    else $(that).removeClass("op-and").addClass("op-or");
    let query_ui = $(that).parent();
    let qParts = $(query_ui).find(".query-part");
    let that_index;
    for (let i = 0; i < qParts.length; i++) {
        if ($(qParts[i]).data('queue') == $(that).data('queue')) {
            that_index = i;
            break;
        }
    }
    let lg_cnt = 1, rg_cnt = 1;
    if (op === "and" || op === "و") {
        //From Index to start
        for (let i = that_index - 2; i >= 0; i--) {
            if ($(qParts[i]).hasClass("query-grouper-end")) {
                lg_cnt++;
            } else if ($(qParts[i]).hasClass("query-grouper-start")) {
                lg_cnt--;
            }
            if (lg_cnt === 0 || i === 0) {
                $(qParts[i]).remove();
                break;
            }
            // if ($(qParts[i]).hasClass("query-grouper-start")) {
            //     $(qParts[i]).remove();
            //     break;
            // }
        }
        //From Index to end
        for (let i = that_index + 2; i < qParts.length; i++) {
            if ($(qParts[i]).hasClass("query-grouper-start")) {
                rg_cnt++;
            } else if ($(qParts[i]).hasClass("query-grouper-end")) {
                rg_cnt--;
            }
            if (rg_cnt === 0 || i === qParts.length - 1) {
                $(qParts[i]).remove();
                break;
            }
            // if ($(qParts[i]).hasClass("query-grouper-end")) {
            //     $(qParts[i]).remove();
            //     break;
            // }
        }
        console.log(qParts, that_index);
        $(qParts[that_index - 1]).remove(); $(qParts[that_index + 1]).remove();
    } else {
        //From Index to start
        for (let i = that_index; i >= 0; i--) {
            if ($(qParts[i]).hasClass("query-grouper-end")) {
                rg_cnt++;
            } else if ($(qParts[i]).hasClass("query-grouper-start")) {
                rg_cnt--;
            }
            if (rg_cnt === 0 || i === 0) {
                qParts[i].outerHTML = `<span class="query-part query-grouper-start"> )</span>` + qParts[i].outerHTML;
                break;
            }
            // if (i === 0)
            //     $(query_ui).prepend(`<span class="query-part query-grouper-start"> )</span>`);
            // else if ($(qParts[i]).hasClass("query-grouper-start")) {
            //     qParts[i].outerHTML = `<span class="query-part query-grouper-start"> )</span>` + qParts[i].outerHTML;
            //     break;
            // }
        }
        // From Index to end
        for (let i = that_index; i < qParts.length; i++) {
            if ($(qParts[i]).hasClass("query-grouper-start")) {
                lg_cnt++;
            } else if ($(qParts[i]).hasClass("query-grouper-end")) {
                lg_cnt--;
            }
            if (lg_cnt === 0 || i === qParts.length - 1) {
                qParts[i].outerHTML =  qParts[i].outerHTML + `<span class="query-part query-grouper-end">( </span>`;
                break;
            }
            // if (i === (qParts.length - 1))
            //     $(query_ui).append(`<span class="query-part query-grouper-end">( </span>`);
            // else if ($(qParts[i]).hasClass("query-grouper-end")) {
            //     qParts[i].outerHTML = qParts[i].outerHTML + `<span class="query-part query-grouper-end">( </span>`;
            //     break;
            // }
        }
        that.outerHTML = `<span class="query-part query-grouper-end">( </span>` + that.outerHTML + `<span class="query-part query-grouper-start"> )</span>`;
    }
    //Colorify:
    qParts = $(query_ui).find(".query-part");
    let color_cnt = 0;
    for (let i = 0; i < qParts.length; i++) {
        if ($(qParts[i]).hasClass("query-grouper-start")) {
            $(qParts[i]).removeClass("qgc0").removeClass("qgc1").removeClass("qgc2").addClass("qgc" + ((color_cnt++)%3));
        } else if ($(qParts[i]).hasClass("query-operator")) {
            $(qParts[i]).removeClass("qgc0").removeClass("qgc1").removeClass("qgc2").addClass("qgc" + ((color_cnt)%3));
        }  else if ($(qParts[i]).hasClass("query-grouper-end")) {
            $(qParts[i]).removeClass("qgc0").removeClass("qgc1").removeClass("qgc2").addClass("qgc" + ((--color_cnt)%3));
        }
    }
}
function resetDataQueue(qParts)
{
    for (let i = 0; i < qParts.length; i++) {
        $("#advanced-search_filters .query-text").find(".query-part:eq(" + i + ")").data("queue", i);
    }
}
function selectGenre(elmnt)
{
    let query_ui = $("#advanced-search_filters .query-text")[0];
    let qParts = $(query_ui).find(".query-part");
    let next_queue = qParts.length;
    let data_id = $(elmnt).data("id");
    if (qParts.length > 0) {
        $(query_ui).append(`<span class="query-part query-operator op-and qgc0" data-queue="`+ (next_queue) +`" onclick="changeOperator(this)">و</span>`);
        next_queue++;
    } else {
        resetQuery();
    }
    $(query_ui).append(`
<span class="query-part query-data" data-queue="`+ (next_queue) +`" data-id="`+data_id+`"  onclick="unselectGenre(this)">`+ $(elmnt).text() +`</span>
`);
    qParts = $(query_ui).find(".query-part");
    resetDataQueue(qParts);
}
function unselectGenre(target)
{
    if (!$(target).hasClass("selected-for-delete")) {
        $(target).addClass("selected-for-delete");
        return;
    }
    let query_ui = $("#advanced-search_filters .query-text")[0];
    let qParts = $(query_ui).find(".query-part");
    let that_index = parseInt($(target).data('queue')),
        start = -1,
        end = -1;
    for (let i = 0; i < qParts.length; i++) {
        if ($(qParts[i]).data("queue") == $(target).data("queue"))
            that_index = i;
    }
    let pointer = that_index - 1;
    while ((start === -1) && (pointer >= 0)) {
        if ($(qParts[pointer--]).hasClass("query-grouper-start")) {
            start = pointer + 2;
            break;
        }
    }
    if (start === -1) start = 0;
    pointer = that_index + 1;
    while ((end === -1) && (pointer <= (qParts.length - 1))) {
        if ($(qParts[pointer++]).hasClass("query-grouper-end")) {
            end = pointer - 2;
            break;
        }
    }
    if (end === -1) end = (qParts.length - 1);
    console.log(start, end);
    console.log(qParts.length);
    if ((start !== end) || (qParts.length === 1)) {
        console.log("Hi");
        if (that_index === start) {
            $(qParts[that_index]).remove();
            $(qParts[that_index + 1]).remove();
        } else {
            $(qParts[that_index]).remove();
            $(qParts[that_index -1]).remove();
        }
    } else {
        let f_cnt = 1, b_cnt = 1;
        for (let i = that_index - 1; i >= 0; i--) {
            if ($(qParts[i]).hasClass('op-or')) {
                break;
            } else {
                if (i !== 0)
                    b_cnt++;
                else
                    b_cnt = 10000;
            }
        }
        for (let i = that_index + 1; i <= (qParts.length - 1); i++) {
            if ($(qParts[i]).hasClass('op-or')) {
                break;
            } else {
                if (i !== (qParts.length - 1))
                    f_cnt++;
                else
                    f_cnt = 10000;
            }
        }
        console.log(f_cnt, b_cnt);
        if ((f_cnt < b_cnt)) {
            $(qParts[that_index + f_cnt]).click();
            $(qParts[that_index]).remove();
            $(qParts[that_index + f_cnt]).remove();
        } else if ((f_cnt >= b_cnt)){
            $(qParts[that_index - b_cnt]).click();
            $(qParts[that_index]).remove();
            $(qParts[that_index - b_cnt]).remove();
        }
    }
    qParts = $(query_ui).find(".query-part");
    resetDataQueue(qParts);
    if ($(qParts).length === 0) {
        if (parseInt($(".filter-resultType .selected").data("gp")) === 1) {
            resetQuery(default_q_ui_text_cinema);
        } else {
            resetQuery(default_q_ui_text_vid);
        }
    }
    // if (start === end)
    // if ($(qParts[that_index - 2]).hasClass("op-or")) {
    //     $(qParts[that_index - 2]).click();
    //     $(qParts[that_index]).remove();
    //     $(qParts[that_index - 2]).remove();
    // } else if ($(qParts[that_index + 2]).hasClass("op-or")) {
    //     $(qParts[that_index + 2]).click();
    //     $(qParts[that_index]).remove();
    //     $(qParts[that_index + 2]).remove();
    // } else if ($(qParts[that_index + 1]).hasClass("op-and")) {
    //     $(qParts[that_index]).remove();
    //     $(qParts[that_index + 1]).remove();
    // } else if ($(qParts[that_index - 1]).hasClass("op-and")){
    //     $(qParts[that_index]).remove();
    //     $(qParts[that_index - 1]).remove();
    // } else {
    //     $(qParts[that_index]).remove();
    // }
}
function resetQuery(msg = '')
{
    $("#advanced-search_filters .query-text")[0].innerHTML = msg;
    if (msg.length === 1) {
        let resultTypes = $(".filter-resultType .filters-resultType-data .selected");
        let group = ( ($(resultTypes[0]).hasClass("filters-resultType-gp")) ? ($(resultTypes[0])) : ($(resultTypes[0]).parent()) );
        if (parseInt($(group).data("gp")) === 1) {
            resetQuery(default_q_ui_text_cinema);
        } else {
            resetQuery(default_q_ui_text_vid);
        }
    }
}
function showMore(btn)
{
    $(btn).parent().toggleClass("more-off");
}
function parseQueryString(elmnt)
{
    let dataString = "(";
    let genreUI = $(elmnt);
    let qParts = $(genreUI).find("span");
    for (let i = 0; i < qParts.length; i++) {
        if ($(qParts[i]).hasClass('query-grouper-start')) {
            dataString += "(";
        } else if($(qParts[i]).hasClass('query-grouper-end')) {
            dataString += ")";
        } else if ($(qParts[i]).hasClass("query-operator")) {
            if ($(qParts[i]).hasClass('op-and')) dataString += " AND ";
            else dataString += " OR ";
        } else {
            dataString += "g| LIKE '%" + $(qParts[i]).data("id") + "|%'";
        }
    }
    if (dataString.length !== 1) {
        dataString += ")";
    } else dataString = "";
    return dataString;
}
function submitAdvSearch(mode)
{
    //mode: 0 => content of this element | 1 => as a string;
    if (mode === 0) {
        var data;
        let resultTypes = $(".filter-resultType .filters-resultType-data .selected");
        if (resultTypes.length === 0) {
            alert("Specify Result Type Please");
            return;
        }
        let group = ( ($(resultTypes[0]).hasClass("filters-resultType-gp")) ? ($(resultTypes[0])) : ($(resultTypes[0]).parent()) );
        let query_ui = $('.query-text');
        let dataString_genre = parseQueryString(query_ui);
        let dur_inps = $(".filters-duration input");
        let durText = {
            min: $(dur_inps[1]).val() + ":" + $(dur_inps[0]).val() + ":00",
            max: $(dur_inps[3]).val() + ":" + $(dur_inps[2]).val() + ":59"
        }
        let dataString_duration = "((dura| >= '" + durText.min + "') AND (dura| <= '" + durText.max + "'))";

        let targetList = [];
        if (resultTypes.length > 0) {
            for (let i = 0; i < resultTypes.length; i++) {
                targetList[i] = parseInt(resultTypes.data("id"));
            }
        }
        let dataString = "";
        switch (parseInt($(group).data("gp"))) {
            case 0:
                let kw = $("textarea[name='keyword']").val().trim();
                let dataString_keyword =
                    "((t| LIKE '%" + kw + "%')" +
                    " OR " +
                    "(desc| LIKE '%" + kw + "%'))";
                dataString = dataString_keyword + " AND " + dataString_duration;
                if (dataString_genre.length > 0) dataString += " AND " + dataString_genre;
                let user_kw = $("textarea[name='username']").val().trim();
                user_kw = "%" + user_kw + "%";
                // let userDataString =
                //     "(u| LIKE '%"+ user_kw +"%')" +
                //     " OR " +
                //     "(fn| LIKE '%"+ user_kw +"%')" +
                //     " OR " +
                //     "(ln| LIKE '%"+ user_kw +"%')";
                data = {data: dataString, target: targetList, user: user_kw};
                break;
            case 1:
                let min = parseInt($("input[name='minYear']").val()), max = parseInt($("input[name='maxYear']").val());
                let dataString_years = "((y| < " + max + ") AND (y| > " + min + "))";
                let dataString_title = "(t| LIKE '%" + $("textarea[name='title']").val().trim() + "%')";
                let dataString_actors = "(ac| LIKE '%" + $("textarea[name='actors']").val().trim() + "%')";
                let dataString_awards = ($("textarea[name='awards']").val().trim().length > 0) ?
                    ("(aw| LIKE '%" + $("textarea[name='awards']").val().trim() + "%')") : ("");
                dataString = dataString_years + " AND " + dataString_title + " AND " + dataString_actors + " AND " + dataString_duration;
                if (dataString_awards.length > 0) dataString += " AND " + dataString_awards;
                if (dataString_genre.length > 0) dataString += " AND " + dataString_genre;
                data = {data: dataString, target: targetList};
                break;
        }
        $.ajax({
            url: site_url + "/aj/advanced_search",
            // url: "https://www.uoozet.com" + "/aj/advanced_search",
            method: "POST",
            data: data,
            dataType: 'json',
            success: function (data, status) {
                if (data.status == 200) {
                    $("#results-cinema").html(data.content);
                }
            }
        });
    }
}
function changeSearchMode(target)
{
    let group = ( ($(target).hasClass("filters-resultType-gp")) ? ($(target)) : ($(target).parent()) )
    let groups = $(group).parent();
    let prev_target = $(groups).find(".filters-resultType-each.selected");
    let prev_group = ( ($(prev_target).hasClass("filters-resultType-gp")) ? ($(prev_target)) : ($(prev_target).parent()) )
    if ($(prev_group).data("gp") !== $(group).data("gp")) {
        $(groups).find(".selected").removeClass("selected");
        $("#advanced-search_filters .filters-genre").addClass("hidden");
        $("#advanced-search_filters .filters-text").addClass("hidden");
        $("#advanced-search_filters .filters-releaseYear").addClass("hidden");
        switch (parseInt($(group).data("gp"))) {
            case 0:
                $("#advanced-search_filters .filters-genre.video").removeClass("hidden");
                $("#advanced-search_filters .filters-text.video").removeClass("hidden");
                resetQuery(default_q_ui_text_vid);
                break;
            case 1:
                $("#advanced-search_filters .filters-releaseYear").removeClass("hidden");
                $("#advanced-search_filters .filters-genre.cinema").removeClass("hidden");
                $("#advanced-search_filters .filters-text.cinema").removeClass("hidden");
                resetQuery(default_q_ui_text_cinema);
                break;
        }
    }
    $(target).toggleClass("selected")
}
//TODO: Add a tooltip for Question Marks Next to Labels of : Awards, Actors
function showHelpInfo(elmnt)
{

}
