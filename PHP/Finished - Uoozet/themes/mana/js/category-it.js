/*
* jQuery UI Cat-it!
*
* @version v2.0 (06/2011)
*
* Copyright 2011, Levy Carneiro Jr.
* Released under the MIT license.
* http://aehlke.github.com/cat-it/LICENSE
*
* Homepage:
*   http://aehlke.github.com/cat-it/
*
* Authors:
*   Levy Carneiro Jr.
*   Martin Rehfeld
*   Tobias Schmidt
*   Skylar Challand
*   Alex Ehlke
*
* Maintainer:
*   Alex Ehlke - Twitter: @aehlke
*
* Dependencies:
*   jQuery v1.4+
*   jQuery UI v1.8+
*/
(function($) {

    $.widget('ui.catit', {
        options: {
            allowDuplicates   : false,
            caseSensitive     : true,
            fieldName         : 'category_id',
            placeholderText   : null,   // Sets `placeholder` attr on input field.
            readOnly          : false,  // Disables editing.
            removeConfirmation: false,  // Require confirmation to remove cats.
            catLimit          : null,   // Max number of cats allowed (null for unlimited).

            // Used for autocomplete, unless you override `autocomplete.source`.
            availableCats     : [],

            // Use to override or add any options to the autocomplete widget.
            //
            // By default, autocomplete.source will map to availableCats,
            // unless overridden.
            autocomplete: {},

            // Shows autocomplete before the user even types anything.
            showAutocompleteOnFocus: false,

            // When enabled, quotes are unneccesary for inputting multi-word cats.
            allowSpaces: false,

            // The below options are for using a single field instead of several
            // for our form values.
            //
            // When enabled, will use a single hidden field for the form,
            // rather than one per cat. It will delimit cats in the field
            // with singleFieldDelimiter.
            //
            // The easiest way to use singleField is to just instantiate cat-it
            // on an INPUT element, in which case singleField is automatically
            // set to true, and singleFieldNode is set to that element. This
            // way, you don't need to fiddle with these options.
            singleField: false,

            // This is just used when preloading data from the field, and for
            // populating the field with delimited cats as the user adds them.
            singleFieldDelimiter: ',',

            // Set this to an input DOM node to use an existing form field.
            // Any text in it will be erased on init. But it will be
            // populated with the text of cats as they are created,
            // delimited by singleFieldDelimiter.
            //
            // If this is not set, we create an input node for it,
            // with the name given in settings.fieldName.
            singleFieldNode: null,

            // Whether to animate cat removals or not.
            animate: true,

            // Optionally set a tabindex attribute on the input that gets
            // created for cat-it.
            tabIndex: null,

            // Event callbacks.
            beforeCatAdded      : null,
            afterCatAdded       : null,

            beforeCatRemoved    : null,
            afterCatRemoved     : null,

            onCatClicked        : null,
            onCatLimitExceeded  : null,


            // DEPRECATED:
            //
            // /!\ These event callbacks are deprecated and WILL BE REMOVED at some
            // point in the future. They're here for backwards-compatibility.
            // Use the above before/after event callbacks instead.
            onCatAdded  : null,
            onCatRemoved: null,
            // `autocomplete.source` is the replacement for catSource.
            catSource: null
            // Do not use the above deprecated options.
        },

        _create: function() {
            // for handling static scoping inside callbacks
            var that = this;

            // There are 2 kinds of DOM nodes this widget can be instantiated on:
            //     1. UL, OL, or some element containing either of these.
            //     2. INPUT, in which case 'singleField' is overridden to true,
            //        a UL is created and the INPUT is hidden.
            if (this.element.is('input')) {
                this.catList = $('<ul></ul>').insertAfter(this.element);
                this.options.singleField = true;
                this.options.singleFieldNode = this.element;
                this.element.addClass('catit-hidden-field');
            } else {
                this.catList = this.element.find('ul, ol').andSelf().last();
            }

            this.catInput = $('<input type="text" />').addClass('ui-widget-content');

            if (this.options.readOnly) this.catInput.attr('disabled', 'disabled');

            if (this.options.tabIndex) {
                this.catInput.attr('tabindex', this.options.tabIndex);
            }

            if (this.options.placeholderText) {
                this.catInput.attr('placeholder', this.options.placeholderText);
            }

            if (!this.options.autocomplete.source) {
                this.options.autocomplete.source = function(search, showChoices) {
                    var filter = search.term.toLowerCase();
                    var choices = $.grep(this.options.availableCats, function(element) {
                        // Only match autocomplete options that begin with the search term.
                        // (Case insensitive.)
                        return (element.toLowerCase().indexOf(filter) === 0);
                    });
                    if (!this.options.allowDuplicates) {
                        choices = this._subtractArray(choices, this.assignedCats());
                    }
                    showChoices(choices);
                };
            }

            if (this.options.showAutocompleteOnFocus) {
                this.catInput.focus(function(event, ui) {
                    that._showAutocomplete();
                });

                if (typeof this.options.autocomplete.minLength === 'undefined') {
                    this.options.autocomplete.minLength = 0;
                }
            }

            // Bind autocomplete.source callback functions to this context.
            if ($.isFunction(this.options.autocomplete.source)) {
                this.options.autocomplete.source = $.proxy(this.options.autocomplete.source, this);
            }

            // DEPRECATED.
            if ($.isFunction(this.options.catSource)) {
                this.options.catSource = $.proxy(this.options.catSource, this);
            }

            this.catList
                .addClass('catit')
                .addClass('ui-widget ui-widget-content ui-corner-all')
                // Create the input field.
                .append($('<li class="catit-new"></li>').append(this.catInput))
                .click(function(e) {
                    var target = $(e.target);
                    if (target.hasClass('catit-label')) {
                        var cat = target.closest('.catit-choice');
                        if (!cat.hasClass('removed')) {
                            that._trigger('onCatClicked', e, {cat: cat, catLabel: that.catLabel(cat)});
                        }
                    } else {
                        // Sets the focus() to the input field, if the user
                        // clicks anywhere inside the UL. This is needed
                        // because the input field needs to be of a small size.
                        that.catInput.focus();
                    }
                });

            // Single field support.
            var addedExistingFromSingleFieldNode = false;
            if (this.options.singleField) {
                if (this.options.singleFieldNode) {
                    // Add existing cats from the input field.
                    var node = $(this.options.singleFieldNode);
                    var cats = node.val().split(this.options.singleFieldDelimiter);
                    node.val('');
                    $.each(cats, function(index, cat) {
                        that.createCat(cat, null, true);
                        addedExistingFromSingleFieldNode = true;
                    });
                } else {
                    // Create our single field input after our list.
                    this.options.singleFieldNode = $('<input type="hidden" style="display:none;" value="" name="' + this.options.fieldName + '" />');
                    this.catList.after(this.options.singleFieldNode);
                }
            }

            // Add existing cats from the list, if any.
            if (!addedExistingFromSingleFieldNode) {
                this.catList.children('li').each(function() {
                    if (!$(this).hasClass('catit-new')) {
                        that.createCat($(this).text(), $(this).attr('class'), true);
                        $(this).remove();
                    }
                });
            }

            // Events.
            this.catInput
                .keydown(function(event) {
                    // Backspace is not detected within a keypress, so it must use keydown.
                    if (event.which == $.ui.keyCode.BACKSPACE && that.catInput.val() === '') {
                        var cat = that._lastCat();
                        if (!that.options.removeConfirmation || cat.hasClass('remove')) {
                            // When backspace is pressed, the last cat is deleted.
                            that.removeCat(cat);
                        } else if (that.options.removeConfirmation) {
                            cat.addClass('remove ui-state-highlight');
                        }
                    } else if (that.options.removeConfirmation) {
                        that._lastCat().removeClass('remove ui-state-highlight');
                    }

                    // Comma/Space/Enter are all valid delimiters for new cats,
                    // except when there is an open quote or if setting allowSpaces = true.
                    // Tab will also create a cat, unless the cat input is empty,
                    // in which case it isn't caught.
                    if (
                        (event.which === $.ui.keyCode.COMMA && event.shiftKey === false) ||
                        event.which === $.ui.keyCode.ENTER ||
                        (
                            event.which == $.ui.keyCode.TAB &&
                            that.catInput.val() !== ''
                        ) ||
                        (
                            event.which == $.ui.keyCode.SPACE &&
                            that.options.allowSpaces !== true &&
                            (
                                $.trim(that.catInput.val()).replace( /^s*/, '' ).charAt(0) != '"' ||
                                (
                                    $.trim(that.catInput.val()).charAt(0) == '"' &&
                                    $.trim(that.catInput.val()).charAt($.trim(that.catInput.val()).length - 1) == '"' &&
                                    $.trim(that.catInput.val()).length - 1 !== 0
                                )
                            )
                        )
                    ) {
                        // Enter submits the form if there's no text in the input.
                        if (!(event.which === $.ui.keyCode.ENTER && that.catInput.val() === '')) {
                            event.preventDefault();
                        }

                        // Autocomplete will create its own cat from a selection and close automatically.
                        if (!(that.options.autocomplete.autoFocus && that.catInput.data('autocomplete-open'))) {
                            that.catInput.autocomplete('close');
                            that.createCat(that._cleanedInput());
                        }
                    }
                }).blur(function(e){
                    // Create a cat when the element loses focus.
                    // If autocomplete is enabled and suggestion was clicked, don't add it.
                    if (!that.catInput.data('autocomplete-open')) {
                        that.createCat(that._cleanedInput());
                    }
                });

            // Autocomplete.
            if (this.options.availableCats || this.options.catSource || this.options.autocomplete.source) {
                var autocompleteOptions = {
                    select: function(event, ui) {
                        that.createCat(ui.item.value);
                        // Preventing the cat input to be updated with the chosen value.
                        return false;
                    }
                };
                $.extend(autocompleteOptions, this.options.autocomplete);

                // catSource is deprecated, but takes precedence here since autocomplete.source is set by default,
                // while catSource is left null by default.
                autocompleteOptions.source = this.options.catSource || autocompleteOptions.source;

                this.catInput.autocomplete(autocompleteOptions).bind('autocompleteopen.catit', function(event, ui) {
                    that.catInput.data('autocomplete-open', true);
                }).bind('autocompleteclose.catit', function(event, ui) {
                    that.catInput.data('autocomplete-open', false);
                });

                this.catInput.autocomplete('widget').addClass('catit-autocomplete');
            }
        },

        destroy: function() {
            $.Widget.prototype.destroy.call(this);

            this.element.unbind('.catit');
            this.catList.unbind('.catit');

            this.catInput.removeData('autocomplete-open');

            this.catList.removeClass([
                'catit',
                'ui-widget',
                'ui-widget-content',
                'ui-corner-all',
                'catit-hidden-field'
            ].join(' '));

            if (this.element.is('input')) {
                this.element.removeClass('catit-hidden-field');
                this.catList.remove();
            } else {
                this.element.children('li').each(function() {
                    if ($(this).hasClass('catit-new')) {
                        $(this).remove();
                    } else {
                        $(this).removeClass([
                            'catit-choice',
                            'ui-widget-content',
                            'ui-state-default',
                            'ui-state-highlight',
                            'ui-corner-all',
                            'remove',
                            'catit-choice-editable',
                            'catit-choice-read-only'
                        ].join(' '));

                        $(this).text($(this).children('.catit-label').text());
                    }
                });

                if (this.singleFieldNode) {
                    this.singleFieldNode.remove();
                }
            }

            return this;
        },

        _cleanedInput: function() {
            // Returns the contents of the cat input, cleaned and ready to be passed to createCat
            return $.trim(this.catInput.val().replace(/^"(.*)"$/, '$1'));
        },

        _lastCat: function() {
            return this.catList.find('.catit-choice:last:not(.removed)');
        },

        _cats: function() {
            return this.catList.find('.catit-choice:not(.removed)');
        },

        assignedCats: function() {
            // Returns an array of cat string values
            var that = this;
            var cats = [];
            if (this.options.singleField) {
                cats = $(this.options.singleFieldNode).val().split(this.options.singleFieldDelimiter);
                if (cats[0] === '') {
                    cats = [];
                }
            } else {
                this._cats().each(function() {
                    cats.push(that.catLabel(this));
                });
            }
            return cats;
        },

        _updateSingleCatsField: function(cats) {
            // Takes a list of cat string values, updates this.options.singleFieldNode.val to the cats delimited by this.options.singleFieldDelimiter
            $(this.options.singleFieldNode).val(cats.join(this.options.singleFieldDelimiter)).trigger('change');
        },

        _subtractArray: function(a1, a2) {
            var result = [];
            for (var i = 0; i < a1.length; i++) {
                if ($.inArray(a1[i], a2) == -1) {
                    result.push(a1[i]);
                }
            }
            return result;
        },

        catLabel: function(cat) {
            // Returns the cat's string label.
            if (this.options.singleField) {
                return $(cat).find('.catit-label:first').text();
            } else {
                return $(cat).find('input:first').val();
            }
        },

        _showAutocomplete: function() {
            this.catInput.autocomplete('search', '');
        },

        _findCatByLabel: function(name) {
            var that = this;
            var cat = null;
            this._cats().each(function(i) {
                if (that._formatStr(name) == that._formatStr(that.catLabel(this))) {
                    cat = $(this);
                    return false;
                }
            });
            return cat;
        },

        _isNew: function(name) {
            return !this._findCatByLabel(name);
        },

        _formatStr: function(str) {
            if (this.options.caseSensitive) {
                return str;
            }
            return $.trim(str.toLowerCase());
        },

        _effectExists: function(name) {
            return Boolean($.effects && ($.effects[name] || ($.effects.effect && $.effects.effect[name])));
        },

        createCat: function(value, additionalClass, duringInitialization) {
            var that = this;

            value = $.trim(value);

            if(this.options.preprocessCat) {
                value = this.options.preprocessCat(value);
            }

            if (value === '') {
                return false;
            }

            if (!this.options.allowDuplicates && !this._isNew(value)) {
                var existingCat = this._findCatByLabel(value);
                if (this._trigger('onCatExists', null, {
                    existingCat: existingCat,
                    duringInitialization: duringInitialization
                }) !== false) {
                    if (this._effectExists('highlight')) {
                        existingCat.effect('highlight');
                    }
                }
                return false;
            }

            if (this.options.catLimit && this._cats().length >= this.options.catLimit) {
                this._trigger('onCatLimitExceeded', null, {duringInitialization: duringInitialization});
                return false;
            }

            var label = $(this.options.onCatClicked ? '<a class="catit-label"></a>' : '<span class="catit-label"></span>').text(value);

            // Create cat.
            var cat = $('<li></li>')
                .addClass('catit-choice ui-widget-content ui-state-default ui-corner-all')
                .addClass(additionalClass)
                .append(label);

            if (this.options.readOnly){
                cat.addClass('catit-choice-read-only');
            } else {
                cat.addClass('catit-choice-editable');
                // Button for removing the cat.
                var removeCatIcon = $('<span></span>')
                    .addClass('ui-icon ui-icon-close');
                var removeCat = $('<a><span class="text-icon">\xd7</span></a>') // \xd7 is an X
                    .addClass('catit-close')
                    .append(removeCatIcon)
                    .click(function(e) {
                        // Removes a cat when the little 'x' is clicked.
                        that.removeCat(cat);
                    });
                cat.append(removeCat);
            }

            // Unless options.singleField is set, each cat has a hidden input field inline.
            if (!this.options.singleField) {
                var escapedValue = label.html();
                cat.append('<input type="hidden" value="' + escapedValue + '" name="' + this.options.fieldName + '" class="catit-hidden-field" />');
            }

            if (this._trigger('beforeCatAdded', null, {
                cat: cat,
                catLabel: this.catLabel(cat),
                duringInitialization: duringInitialization
            }) === false) {
                return;
            }

            if (this.options.singleField) {
                var cats = this.assignedCats();
                cats.push(value);
                this._updateSingleCatsField(cats);
            }

            // DEPRECATED.
            this._trigger('onCatAdded', null, cat);

            this.catInput.val('');

            // Insert cat.
            this.catInput.parent().before(cat);

            this._trigger('afterCatAdded', null, {
                cat: cat,
                catLabel: this.catLabel(cat),
                duringInitialization: duringInitialization
            });

            if (this.options.showAutocompleteOnFocus && !duringInitialization) {
                setTimeout(function () { that._showAutocomplete(); }, 0);
            }
        },

        removeCat: function(cat, animate) {
            animate = typeof animate === 'undefined' ? this.options.animate : animate;

            cat = $(cat);

            // DEPRECATED.
            this._trigger('onCatRemoved', null, cat);

            if (this._trigger('beforeCatRemoved', null, {cat: cat, catLabel: this.catLabel(cat)}) === false) {
                return;
            }

            if (this.options.singleField) {
                var cats = this.assignedCats();
                var removedCatLabel = this.catLabel(cat);
                cats = $.grep(cats, function(el){
                    return el != removedCatLabel;
                });
                this._updateSingleCatsField(cats);
            }

            if (animate) {
                cat.addClass('removed'); // Excludes this cat from _cats.
                var hide_args = this._effectExists('blind') ? ['blind', {direction: 'horizontal'}, 'fast'] : ['fast'];

                var thisCat = this;
                hide_args.push(function() {
                    cat.remove();
                    thisCat._trigger('afterCatRemoved', null, {cat: cat, catLabel: thisCat.catLabel(cat)});
                });

                cat.fadeOut('fast').hide.apply(cat, hide_args).dequeue();
            } else {
                cat.remove();
                this._trigger('afterCatRemoved', null, {cat: cat, catLabel: this.catLabel(cat)});
            }

        },

        removeCatByLabel: function(catLabel, animate) {
            var toRemove = this._findCatByLabel(catLabel);
            if (!toRemove) {
                throw "No such cat exists with the name '" + catLabel + "'";
            }
            this.removeCat(toRemove, animate);
        },

        removeAll: function() {
            // Removes all cats.
            var that = this;
            this._cats().each(function(index, cat) {
                that.removeCat(cat, false);
            });
        }

    });
})(jQuery);

