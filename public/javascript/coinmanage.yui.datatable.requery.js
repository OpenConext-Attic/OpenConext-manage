YAHOO.widget.DataTable.prototype.reQuery = function() {
    var paginator = this.get('paginator');
    var state = paginator.getState({
        page: paginator.getCurrentPage(),
        searchQuery: 'owhai!'
    });
    this.onPaginatorChangeRequest(state);
};