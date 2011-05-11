var COINMANAGE = {
};

COINMANAGE.AjaxDataTable = function(selector) {
    var _dataSourceUrl, // required
        _dataSourceFields,  // required
        _displayColumns,  // required
        _limit,
        _sortedField,
        _sortedDir = 'asc',
        _onRecordClick,
        _recordActions = [],
        _node;

    if (!selector) {
        throw "No selector for DataTable";
    }

    _node = YAHOO.util.Selector.query(selector, undefined, true);
    if (!_node) {
        throw "Unable to find node in DOM for selector: " + selector;
    }

    function _validate() {
        if (!_dataSourceUrl) {
            throw "No DataSource URL set for datatable: " + selector;
        }

        if (!_dataSourceFields) {
            throw "No DataSource fields set for datatable: " + selector;
        }

        if (!_displayColumns || _displayColumns.length === 0) {
            throw "No columns to display for datatable: " + selector;
        }
    }

    return {
        setLoadUrl: function (url) {
            _dataSourceUrl = url;
            return this;
        },

        setLoadFields: function (columns) {
            _dataSourceFields = columns;
            return this;
        },

        setDisplayColumns: function (columns) {
            _displayColumns = columns;
            return this;
        },

        onRecordClick: function (callback) {
            _onRecordClick = callback;
            return this;
        },

        setLimit: function(limit) {
            _limit = limit;
            return this;
        },

        /**
         * Add a record action.
         *
         * Available options:
         * - imgAlt
         * - imgSrc
         * - onClick
         * - href
         * - title
         * @param options
         */
        addRecordAction: function(name, options) {
            options.name = name;
            _recordActions[_recordActions.length] = options;
            return this;
        },

        setSortedBy: function(field) {
            _sortedField = field;
            return this;
        },

        setSortedDir: function(dir) {
            _sortedDir = dir;
            return this;
        },

        render: function() {
            _validate();
            console.log(_displayColumns);

            this.DataSource = new YAHOO.util.XHRDataSource(_dataSourceUrl);
            this.DataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
            this.DataSource.connXhrMode = "queueRequests";
            this.DataSource.responseSchema = {
                resultsList: "ResultSet",
                fields: _dataSourceFields,
                metaFields: {
                    totalRecords: "totalRecords" // Access to value in the server response
                }
            };

            // DataTable configuration
            var Configs = {
                initialRequest: "", // Initial request for first page of data
                dynamicData: true // Enables dynamic server-driven data
            };
            if (_sortedField) {
                Configs.sortedBy = {
                    key: _sortedField
                };
                if (_sortedDir && _sortedDir==='desc') {
                    Configs.sortedBy.dir = YAHOO.widget.DataTable.CLASS_DESC;
                }
                else if (_sortedDir && _sortedDir==='asc') {
                    Configs.sortedBy.dir = YAHOO.widget.DataTable.CLASS_ASC;
                }
            }
            if (_limit) {
                Configs.paginator = new YAHOO.widget.Paginator({
                    rowsPerPage: _limit
                });
            }

            var DataTable = new YAHOO.widget.DataTable(_node, _displayColumns, this.DataSource, Configs);

            return DataTable;
        }
    };
};