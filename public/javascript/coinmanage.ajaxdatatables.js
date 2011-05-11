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
            var DataTable;

            _validate();

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

            if (_recordActions && _recordActions.length > 0) {
                _displayColumns[_displayColumns.length] = {
                    label: "",
                    key: 'action',
                    formatter: function(el, record) {
                        console.log(arguments);
                        var actionNode, img, link, recordAction;
                        for (var i=0; i < _recordActions.length; i++) {
                            recordAction = _recordActions[i];
                            actionNode = el.appendChild(document.createElement('span'));
                            actionNode.className = 'record-action';

                            link = null;
                            if (recordAction.onClick) {
                                link = actionNode.appendChild(document.createElement('a'));
                                link.href = '#';
                                YAHOO.util.Event.addListener(link, "click", function() {
                                    recordAction.onClick(el, record, DataTable);
                                    return false;
                                });
                                if (recordAction.title) {
                                    link.title = recordAction.title;
                                }
                            }
                            if (recordAction.imgSrc) {
                                if (link) {
                                    img = link.appendChild(document.createElement('img'));
                                }
                                else {
                                    img = actionNode.appendChild(document.createElement('img'));
                                }
                                img.src = recordAction.imgSrc;
                                if (recordAction.imgAlt) {
                                    img.alt = recordAction.imgAlt;
                                }
                                if (recordAction.title) {
                                    img.title = recordAction.title;
                                }
                            }
                            el.appendChild(actionNode);
                        }
                    }
                };
            }

            DataTable = new YAHOO.widget.DataTable(_node, _displayColumns, this.DataSource, Configs);

            if (_onRecordClick) {
                DataTable.subscribe("rowMouseoverEvent", function() {
                    DataTable.onEventHighlightRow.apply(DataTable, arguments);
                });
                DataTable.subscribe("rowMouseoutEvent", function() {
                    DataTable.onEventUnhighlightRow.apply(DataTable, arguments);
                });

                DataTable.subscribe("rowClickEvent", function(e) {
                    DataTable.onEventSelectRow.apply(DataTable, arguments);

                    if (e.target.nodeName === "IMG") {
                        // Ignore clicks on record actions
                        return false;
                    }

                    _onRecordClick(DataTable);

                    return false;
                });
            }

            return DataTable;
        }
    };
};