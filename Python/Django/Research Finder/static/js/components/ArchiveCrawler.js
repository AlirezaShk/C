class BaseArchiveCrawler{
	constructor(csrf_token, type, perPage, resultPages, applyFilter, isMounted, sortBy) {
		this.type = type;
		this.perPage = perPage;
		this.resultPages = resultPages;
		this.isMounted = isMounted;
		this.sortBy = sortBy;
        this.csrf_token = csrf_token;

        this.deleteOk = true
        this.addOk = true
        this.loadingStatus = false
        let that = this

        $.ajax({
            url: '/api/filter/get',
            type: 'get',
            headers: {'X-CSRFToken': this.csrf_token},
            dataType: 'json',
            success: function (filters) {
                that.filterTypeToLabel = filters
            }
        })
        this.newFilterTemplate = ({code, label, filterType, value}) => `<button type="button" class="btn btn-outline-danger p-2 mt-2 me-2" data-code='${code}'>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"></path>
            </svg>
            <span>${label}</span>
            <input type="text" value="${value}" data-f-type="${filterType}">
        </button>`;

        this.debounce = function (func, wait, immediate) {
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
        this.filterIt()
	}
	/**
     * Instead of submission, use app.apply() to connect to API
     * 
     * @function app.applyFilter()
     **/

    beforeSubmit(event) {
        event.preventDefault();
        this.applyFilter(
            $("#filterType").val().trim(), 
            $("#search-form input[name='filter']").val().trim(),
            this.type
        );
    }

    /**
     * Soft filter the results table rows and form pages
     * 
     * @function ArchiveCrawler.apply()
     **/

    toggleLoading() {
        this.loadingStatus = ! this.loadingStatus
    }

    filterIt() {
        let that = this;

        //Debounce it to ensure user has stopped typing which results in reduced workload
        let k = this.debounce(function () {
            that.toggleLoading();
            //Get Data
            let filters = $("#currentFilters button")
            let rows = $("#results > div")
            $(rows).removeClass('filtered')
            let invalid_rows = []

            console.log("STARTING FILTER")
            for(let i = 0; i < filters.length; i++) {
                for(let j = 0; j < rows.length && !invalid_rows.includes(rows[j]); j++) {
                    if(that.apply(rows[j], filters[i]) == -1) {
                        invalid_rows.push(rows[j])
                    }
                }
            }

            for(let i = 0; i < invalid_rows.length; i++) {
                $(invalid_rows[i]).addClass('filtered')
            }



            // //Get Data
            // let input = $("#search-input").val();
            // let table = $(".results-table")[0];
            // let rows = $(table).find("tbody > tr:not(#zero-result):not(#table-loader):not(.filtered)");
            // $(rows).removeClass('included');
            // //Sort Data
            // let sortCol = $(".results-table th.sort-by")[0];
            // let sortByIndex = $(".results-table th").index(sortCol);
            // if(!$(sortCol).hasClass('sort-applied')) {
            //     let reverse = $(sortCol).hasClass('sort-reversed');
            //     let is_total_books = (
            //         ($($(rows[0]).find("td:not(.tw-hidden)")[sortByIndex]).data('cat') == 'books') || 
            //         ($($(rows[0]).find("td:not(.tw-hidden)")[sortByIndex]).data('cat') == 'id')
            //     );
            //     rows = rows.sort(function (a, b) {
            //         let txta = $(a).find("td:not(.tw-hidden)")[sortByIndex].innerText;
            //         let txtb = $(b).find("td:not(.tw-hidden)")[sortByIndex].innerText;
            //         let r;
            //         //Id Column
            //         if(sortByIndex !== 0)
            //             r = txta.localeCompare(txtb);
            //         //Other columns
            //         else{
            //             txta = parseInt(txta);
            //             txtb = parseInt(txtb);
            //             if( txta > txtb ) r = -1;
            //             else if ( txta < txtb ) r = 1;
            //             else r = 0;
            //         }
            //         if (is_total_books) {
            //             r *= -1;
            //         }
            //         if (reverse) {
            //             r *= -1;
            //         }
            //         return r;
            //     });
            //     $(sortCol).addClass('sort-applied');
            // }
            // //Apply filter and extract valid and total rows
            // let valid_rows = [], total_rows = [];
            // for(let i = 0; i < rows.length; i++) {
            //     //case insensitive search for the input value inside each row of the results table
            //     if (that.apply(rows[i], input) != -1) {
            //         valid_rows.push(rows[i]);
            //         $(rows[i]).addClass('included');
            //     }
            //     total_rows.push(rows[i]);
            // }
            // let pages = [];
            // let j = -1;
            // let k = 0;
            // do {
            //     pages.push([]);
            //     j++;
            //     for(let i = 0; i < Math.min(that.perPage, valid_rows.length); i++) {
            //         pages[j].push(valid_rows[i + j*that.perPage]);
            //         k++;
            //     }
            // } while(valid_rows.length > k);
            // // that.$emit('searched', {total: total_rows, valid: pages});

            that.toggleLoading();
        }, 500);
        k();
    }

    /**
     * Apply the Soft Filter
     * 
     * @param Object subject
     * Table rows to apply the soft filteration to.
     * 
     * @param String | undefined type
     * By default, it will pick the value from #filterType, unless provided.
     * This is the attribute short code which is the type of filteration.
     * 
     * @return -1 | !(-1)
     **/

     apply(subject, filter) {
        filter = $(filter).find("input")
        filter = {
            'type': $(filter).data('f-type'),
            'value': $(filter).val().trim()
        }
        console.log({'k':filter.type, 'v':filter.value, 'r': $(subject).find('[data-cat="'+filter.type+'"]')[0]})
        if (filter.type != 'free') {
            subject = $(subject).find('[data-cat="'+filter.type+'"]')[0]
        }
        return $(subject).text().search(new RegExp(filter.value, 'i'))
     }
    // apply(subject, input, type = undefined) {
    //     if(input.trim().length === 0) return 1;
    //     if (type == undefined) {
    //         type = $("#filterType").val();
    //     }
    //     type = type.trim();
    //     switch(type) {
    //         case 'f':
    //             return subject.textContent.search(new RegExp(input, 'i'));
    //         case 'id':
    //             let id = $(subject).find('[data-cat=id]')[0];
    //             return id.textContent == input ? 1 : -1;
    //         case 'l':
    //             let langs = $(subject).find('[data-cat=langs]')[0].textContent;
    //             if ($(subject).find('[data-cat=olang]').length > 0) 
    //                 langs += $(subject).find('[data-cat=olang]')[0].textContent;
    //             return langs.search(new RegExp(input, 'i'));
    //         case 'ud':
    //             let updateD = $(subject).find('[data-cat=updated_at]')[0];
    //             return updateD.textContent.search(new RegExp(input, 'i'));
    //         case 'cd':
    //             let createD = $(subject).find('[data-cat=created_at]')[0];
    //             return createD.textContent.search(new RegExp(input, 'i'));
    //         // books
    //         case 't':
    //             let title = $(subject).find('[data-cat=name]')[0];
    //             return title.textContent.search(new RegExp(input, 'i'));
    //         case 'i':
    //             let isbn = $(subject).find('[data-cat=isbn]')[0];
    //             return isbn.textContent.search(new RegExp(input, 'i'));
    //         case 'g':
    //             let genre = $(subject).find('[data-cat=genre]')[0];
    //             return genre.textContent.search(new RegExp(input, 'i'));
    //         case 'au':
    //             let author = $(subject).find('[data-cat=author]')[0];
    //             return author.textContent.search(new RegExp(input, 'i'));
    //         case 'ai':
    //             let author_id = $(subject).find('[data-cat=author_id]')[0];
    //             return author_id.textContent == input ? 1 : -1;
    //         case 'rd':
    //             let releaseD = $(subject).find('[data-cat=release_date]')[0];
    //             return releaseD.textContent.search(new RegExp(input, 'i'));
    //         // authors
    //         case 'n':
    //             let name = $(subject).find('[data-cat=name]')[0];
    //             return name.textContent.search(new RegExp(input, 'i'));
    //         case 'o':
    //             let origin = $(subject).find('[data-cat=origin]')[0];
    //             return origin.textContent.search(new RegExp(input, 'i'));
    //         case 'b':
    //             let birth = $(subject).find('[data-cat=birth]')[0];
    //             return birth.textContent.search(new RegExp(input, 'i'));
    //         case 'd':
    //             let death = $(subject).find('[data-cat=death]')[0];
    //             return death.textContent.search(new RegExp(input, 'i'));
    //     }
    // }
    /**
     * In the mount function, firstly await the hard filters to be
     * applied via ArchiveCrawler.CurrentFilters. Then set the 
     * onlick event handler on the headings of the table that will
     * manage the sorting process.
     * 
     * @function ArchiveCrawler.filterIt()
     **/
    mounted() {
        // await (this.$refs.CurrentFilters.isMounted === true)
        this.filterIt();
        this.isMounted = true;
        let headings = $('.results-table thead th:not(:last-child)');
        let that = this;
        $(headings).click(function(e){
            $('th.sort-applied').removeClass('sort-applied');
            if(!$(e.target).hasClass('sort-by')) {
                $("th.sort-by").removeClass('sort-by');
                $(e.target).addClass('sort-by');
            } else { 
                $(e.target).toggleClass('sort-reversed'); 
            }
            that.filterIt();
        });
    }

    toggleSearchField(filterElem) {
        $(filterElem).find("span:not(.dropdown-item-text)").toggleClass("active");
    }

    toggleSortField(sortElem) {
        let icon = $(sortElem).find("i")[0];
        if ($(icon).hasClass("bi-dash")) {
            $("#search-bar ul.sorters li > span.active").parent().find("i")
                .removeClass("bi-chevron-up")
                .removeClass("bi-chevron-down")
                .addClass("bi-dash");
            $("#search-bar ul.sorters li > span.active")
                .removeClass("active");
            $(sortElem).find("span").addClass("active");
            $(icon).removeClass("bi-dash");
            $(icon).addClass("bi-chevron-up");
        } else if ($(icon).hasClass("bi-chevron-up")) {
            $(icon).removeClass("bi-chevron-up");
            $(icon).addClass("bi-chevron-down");
        } else {
            $(icon).removeClass("bi-chevron-down");
            $(icon).addClass("bi-dash");
            $(sortElem).find("span").removeClass("active");
        }
    }

    applyFilter(data) {
        if(this.addOk) {
            this.addOk = false
            let that = this
            $.ajax({
                url: '/api/filter/add',
                type: 'post',
                headers: {'X-CSRFToken': this.csrf_token},
                data: {"data": data},
                dataType: 'json',
                success: function (status) {
                    if(status.success) {
                        let currentFiltersWrapper = $("#currentFilters")
                        let filterCount = parseInt($(currentFiltersWrapper).find('button').length)
                        let elems = []
                        for(let i = 0; i < data.length; i++)
                            elems.push({
                                code: filterCount + i, 
                                label: that.filterTypeToLabel[data[i].type],
                                filterType: data[i].type,
                                value: data[i].val,
                            })
                        $(currentFiltersWrapper).append(elems.map(that.newFilterTemplate).join(''))
                    }
                },
                complete: function () {
                    that.addOk = true
                    that.configureCurrentFilters()
                    that.filterIt()
                }
            });
        }
    }

    deleteFilter(key) {
        if(this.deleteOk){
            this.deleteOk = false
            let that = this
            $.ajax({
                url: '/api/filter/delete',
                type: 'post',
                headers: {'X-CSRFToken': this.csrf_token},
                data: {"key": key},
                dataType: 'json',
                success: function (data) {
                    if(data.success){
                        //Delete the target from UI
                        $("#currentFilters > button[data-code='"+key+"']").remove()
                        //Reset Keys:
                        let currentFilters = $("#currentFilters > button")
    
                        for(let i = 0; i < currentFilters.length; i++) {
                            $(currentFilters[i]).attr('data-code', i)
                        }
                    }
                },
                complete: function () {
                    that.deleteOk = true
                    that.filterIt()
                }
            });
        }
    }

    configureCurrentFilters() {
        let that = this

        $("#currentFilters > button > svg").click(function (e) {
            let key = parseInt($(this).parent().data("code"));
            that.deleteFilter(key);
        });

        $("#currentFilters > button").each(function () {
            let inp = $(this).find("input[type='text']");
            inp.css("width", inp.val().length + 1 + "ch");
        })

        $("#currentFilters > button > input[type='text']").keyup(function (e) {
            $(this).css("width", $(this).val().length + 1 + "ch");
        }).change(function (e) {
            $(this).css("width", $(this).val().length + 1 + "ch");
            that.filterIt()
        });
    }
}