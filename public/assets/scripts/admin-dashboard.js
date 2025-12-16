// ===== Work Orders Table Filter =====
FooTable.WorkOrdersFiltering = FooTable.Filtering.extend({
    construct: function(instance) {
        this._super(instance);
        this.statuses = window.statusesFromDB || [];
        this.defaultStatus = 'All';
        this.$statusSelect = null;
    },

    $create: function() {
        this._super();
        var self = this;

        // Filter wrapper
        var $wrapper = $('<div/>', { class: 'form-group dm-select d-flex align-items-center my-xl-25 my-15 me-sm-20 me-0' })
            .append($('<label/>', { class: 'd-flex align-items-center mb-sm-0 mb-2', text: 'Status' }))
            .prependTo(self.$form);

        // Dropdown
        self.$statusSelect = $('<select/>', { class: 'form-control ms-sm-10 ms-0' })
            .append($('<option/>', { text: self.defaultStatus, value: '' }))
            .on('change', { self: self }, self._onStatusChanged)
            .appendTo($wrapper);

        // Add DB statuses
        self.statuses.forEach(status => {
            self.$statusSelect.append($('<option/>', { text: status.name, value: status.name }));
        });
    },

    _onStatusChanged: function(e) {
        var self = e.data.self;
        var selected = $(this).val();
        if (selected) {
            self.addFilter('status', selected, row => $(row).find('[data-name="status"]').text().trim() === selected);
        } else {
            self.removeFilter('status');
        }
        self.filter();
    },

    draw: function() {
        this._super();
        var statusFilter = this.find('status');
        this.$statusSelect.val(statusFilter instanceof FooTable.Filter ? statusFilter.query.val() : '');
    }
});

// ===== Pending Registrations Table Filter =====
FooTable.PendingFiltering = FooTable.Filtering.extend({
    construct: function(instance) {
        this._super(instance);
        this.statuses = ['Pending', 'Approved', 'Denied']; // or dynamic if needed
        this.defaultStatus = 'All';
        this.$statusSelect = null;
    },

    $create: function() {
        this._super();
        var self = this;

        var $wrapper = $('<div/>', { class: 'form-group dm-select d-flex align-items-center my-xl-25 my-15 me-sm-20 me-0' })
            .append($('<label/>', { class: 'd-flex align-items-center mb-sm-0 mb-2', text: 'Status' }))
            .prependTo(self.$form);

        self.$statusSelect = $('<select/>', { class: 'form-control ms-sm-10 ms-0' })
            .append($('<option/>', { text: self.defaultStatus, value: '' }))
            .on('change', { self: self }, self._onStatusChanged)
            .appendTo($wrapper);

        self.statuses.forEach(status => {
            self.$statusSelect.append($('<option/>', { text: status, value: status }));
        });
    },

    _onStatusChanged: function(e) {
        var self = e.data.self;
        var selected = $(this).val();
        if (selected) {
            self.addFilter('status', selected, row => $(row).find('.userDatatable-content-status').text().trim() === selected);
        } else {
            self.removeFilter('status');
        }
        self.filter();
    },

    draw: function() {
        this._super();
        var statusFilter = this.find('status');
        this.$statusSelect.val(statusFilter instanceof FooTable.Filter ? statusFilter.query.val() : '');
    }
});

// ===== Initialize both tables =====
$(function() {
    // Work Orders
    $('#work-orders-table').footable({
        components: { filtering: FooTable.WorkOrdersFiltering },
        filtering: { enabled: true },
        paging: { enabled: true, size: 10 }
    });

    // Pending Registrations
    $('#pending-registrations-table').footable({
        components: { filtering: FooTable.PendingFiltering },
        filtering: { enabled: true },
        paging: { enabled: true, size: 10 }
    });
});
