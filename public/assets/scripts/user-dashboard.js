$(function () {
    // Extend FooTable with custom filtering
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

            // Wrapper for filter dropdown
            const $wrapper = $('<div/>', {
                class: 'form-group dm-select d-flex align-items-center my-xl-25 my-15 me-sm-20 me-0'
            }).append(
                $('<label/>', {
                    class: 'd-flex align-items-center mb-sm-0 mb-2',
                    text: 'Status'
                })
            ).prependTo(self.$form);

            // Dropdown select
            self.$statusSelect = $('<select/>', { class: 'form-control ms-sm-10 ms-0' })
                .append($('<option/>', { text: self.defaultStatus, value: '' }))
                .on('change', { self: self }, self._onStatusChanged)
                .appendTo($wrapper);

            // Populate statuses from DB
            self.statuses.forEach(status => {
                self.$statusSelect.append(
                    $('<option/>', { text: status.name, value: status.name })
                );
            });
        },

        _onStatusChanged(e) {
            const self = e.data.self;
            const selected = $(this).val();

            if (selected) {
                // Filter rows based on `data-status` attribute
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
