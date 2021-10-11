class BaseArchive{constructor(csrf_token){this.csrf_token=csrf_token;this.Crawler=new BaseArchiveCrawler(csrf_token);}
turnPage(page){let table=$('.results-table')[0];switch(page){case 0:break;default:this.$set(this,'resultsTableIsLoading',true);page=this.archivePage+page;break;}
this.archivePage=page;$(table).find('tbody > tr:not(#table-loader)').addClass('tw-hidden');$(table).find('tbody > tr[page='+page+']').removeClass('tw-hidden');$(table).find('tbody > tr#zero-result').addClass('tw-hidden');if(this.totalPages==1){this.showBack=false;this.showNext=false;if($(table).find('tbody > tr.tw-hidden').length==$(table).find('tbody > tr:not(#table-loader)').length){$(table).find('tbody > tr#zero-result').removeClass('tw-hidden');}}else if(this.totalPages==page+1){this.showBack=true;this.showNext=false;}else if(page==0){this.showBack=false;this.showNext=true;}else{this.showBack=true;this.showNext=true;}
this.$set(this,'resultsTableIsLoading',false);}
paginateResults(pages){let table=$('.results-table')[0];$(table).find('tbody > tr:not(#zero-result):not(#table-loader)').remove();let j=0,k=0;for(let i=0;i<pages.total.length;i++){$(pages.total[i]).removeClass('odd').removeClass('even');try{if(pages.valid[j].includes(pages.total[i])){$(pages.total[i]).attr('page',j);$(pages.total[i]).addClass(k%2?'even':'odd');if(++k==pages.valid[j].length){k=0;j++;}}else{$(pages.total[i]).attr('page','');}}catch(e){$(pages.total[i]).attr('page','');}
$(table).find('tbody').append(pages.total[i]);}
if(pages.valid==undefined)
this.totalPages=1;else
this.totalPages=pages.valid.length;this.showBack=false;this.showNext=true;this.turnPage(0);}
showExtra(e){let nav=$('nav.page-navbar')[0];let target=e.target;if(!$(target).hasClass('extra-link-wrapper')){let index=$(nav).find(".nav-item").index(e.target);let extraLinks=$(nav).find('.extra-link-wrapper.tw-col-start-'+(index+1));if(extraLinks){$(nav).attr('showing-extra',index);extraLinks.addClass('shown');}}else{let extraLinks=$(target);if(!extraLinks.hasClass('shown')){extraLinks.addClass('shown');}
extraLinks.addClass('locked');}}
hideExtra(e){let nav=$('nav.page-navbar')[0];let target=e.target;if(!$(target).hasClass('extra-link-wrapper')){let index=$(nav).find(".nav-item").index(e.target);let prevIndex=parseInt($('nav').attr('showing-extra'));if(index===prevIndex){let extraLinks=$(nav).find('.extra-link-wrapper.tw-col-start-'+(index+1));if(!extraLinks.hasClass('locked'))
extraLinks.removeClass('shown');}}else{let extraLinks=$(target);extraLinks.removeClass('shown').removeClass('locked');}}
resultsTableLoading(){this.$set(this,'resultsTableIsLoading',true);}
searchCallBack(e){this.paginateResults({total:e.total.slice(0),valid:e.valid.slice(0),});}};