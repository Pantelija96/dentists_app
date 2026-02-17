$(function () {
    FooTable.MyFiltering = FooTable.Filtering.extend({

        construct(instance) {
            this._super(instance);
            this.statuses = window.statusesFromDB || [];
            this.defaultStatus = 'All';
            this.$statusSelect = null;
        },

        $create() {
            this._super();
            const self = this;

            if (self.$input) {
                self.$input.attr(
                    'placeholder',
                    window.translations?.search || 'Search'
                );
            }

            const $wrapper = $('<div/>', {
                class: 'form-group dm-select d-flex align-items-center my-xl-25 my-15 me-sm-20 me-0'
            }).append(
                $('<label/>', {
                    class: 'd-flex align-items-center mb-sm-0 mb-2',
                    text: 'Status'
                })
            ).prependTo(self.$form);

            self.$statusSelect = $('<select/>', { class: 'form-control ms-sm-10 ms-0' })
                .append($('<option/>', { text: window.translations?.all || self.defaultStatus, value: '' }))
                .on('change', { self: self }, self._onStatusChanged)
                .appendTo($wrapper);

            self.statuses.forEach(status => {
                self.$statusSelect.append(
                    $('<option/>', { text: status.traslation, value: status.traslation })
                );
            });
        },

        _onStatusChanged(e) {
            const self = e.data.self;
            const selected = $(this).val();

            if (selected) {
                self.addFilter('status', selected, row => $(row).data('status') === selected);
            } else {
                self.removeFilter('status');
            }

            self.filter();
        },

        draw() {
            this._super();
            const statusFilter = this.find('status');
            this.$statusSelect.val(statusFilter instanceof FooTable.Filter ? statusFilter.query.val() : '');
        }
    });

    // Initialize FooTable
    $('.adv-table').footable({
        paging: { enabled: true, current: 1, size: 10 },
        filtering: { enabled: true },
        components: { filtering: FooTable.MyFiltering }
    });
});
