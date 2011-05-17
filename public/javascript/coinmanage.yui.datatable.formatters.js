YAHOO.widget.DataTable.Formatter.entityValid = function(el, oRecord, oColumn, oData) {
    var myDataSource = new YAHOO.util.DataSource("/service-registry/validate-entity?");
        myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
        myDataSource.responseSchema = {
            resultsList: "Response.Results",
            metaFields : {
                link : "Response.Link"
            }
        };

        var mySuccessHandler = function(entityId, response) {
            if (response.results[0].Errors.length > 0) {
                el.innerHTML += '<a href="' + response.meta.link + '" target="_blank"><img src="/images/icons/bullet_red.png" alt="Errors!" title="' +
                        response.results[0].Errors.join(" | \n") +
                '" /></a>';
            }
            if (response.results[0].Warnings.length > 0) {
                el.innerHTML += '<a href="' + response.meta.link + '" target="_blank"><img src="/images/icons/bullet_orange.png" alt="Warnings!" title="' +
                        response.results[0].Warnings.join(" | \n") +
                '" /></a>';
            }
        };
        var myFailureHandler = function() {
        };
        var callbackObj = {
            success : mySuccessHandler,
            failure : myFailureHandler
        };

        myDataSource.sendRequest("eid=" + encodeURIComponent(oData), callbackObj);
};


YAHOO.widget.DataTable.Formatter.accepticon = function(el, oRecord, oColumn, oData) {
    if (YAHOO.lang.isString(oData)) {
        var alt = "No";
        var icon = 'bullet_red.png';
        if (oData == 'T') {
            alt = "Yes";
            icon = 'bullet_green.png';
        }
        el.innerHTML = '<img src="/images/icons/' + icon + '" alt="' + alt + '" />';
    } else {
        el.innerHTML = YAHOO.lang.isValue(oData) ? oData : "";
    }
};

YAHOO.widget.DataTable.Formatter.screenshot = function(el, oRecord, oColumn, oData) {
    if (YAHOO.lang.isString(oData)) {
        var icon = 'cancel.png';
        if (oData != '') {
            url = '<?php echo $this->config->host ?>' + oData;
            el.innerHTML = '<img src="' + url + '"/>';
        }

    } else {
        el.innerHTML = YAHOO.lang.isValue(oData) ? oData : "";
    }
};

YAHOO.widget.DataTable.Formatter.xmllink = function(el, oRecord, oColumn, oData) {
    if (YAHOO.lang.isString(oData)) {
        var icon = 'cancel.png';
        if (oData != '') {
            el.innerHTML = '<a href="' + oData + '" target="_blank"/>[xml]</a>';
        }

    } else {
        el.innerHTML = YAHOO.lang.isValue(oData) ? oData : "";
    }
};