/**
 * SURFconext Manage
 *
 * LICENSE
 *
 * Copyright 2011 SURFnet bv, The Netherlands
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @category  SURFconext Manage
 * @package
 * @copyright Copyright © 2010-2011 SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

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
        var entity = response.results[0];
        if (entity.Errors.length > 0) {
            el.innerHTML += '<a href="' + response.meta.link + '" target="_blank">'+
                '<img src="/images/icons/exclamation.png" alt="Errors!" title="' +
                entity.Errors.join(" | \n") +
                '" /></a>';
        }
        if (entity.Warnings.length > 0) {
            el.innerHTML += '<a href="' + response.meta.link + '" target="_blank">'+
                '<img src="/images/icons/error.png" alt="Warnings!" title="' +
                entity.Warnings.join(" | \n") +
                '" /></a>';
        }
        if (entity.Errors.length === 0 && entity.Warnings.length === 0) {
            el.innerHTML += '<a href="' + response.meta.link + '" target="_blank">'+
                '<img src="/images/icons/tick.png" alt="Valid" title="No errors or warnings, entity valid" /></a>';        
        }
    };
    var myFailureHandler = function() {
    };
    var callbackObj = {
        success : mySuccessHandler,
        failure : myFailureHandler
    };

    var entityId = oRecord.getData('entityid');
    if (!entityId && 'console' in window && 'error' in window.console && typeof window.console.error === "function") {
        window.console.error("Entity ID missing in record, unable to perform validations!");
    }
    myDataSource.sendRequest("eid=" + encodeURIComponent(entityId), callbackObj);
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

YAHOO.widget.DataTable.Formatter.list = function(el, oRecord, oColumn, oData) {
    if (YAHOO.lang.isArray(oData)) {
        var html = '<ul class="datatable_ul">';
        var value = '';
        for (var i = 0; i < oData.length; i++) {
            value = '';
            if (YAHOO.lang.isArray(oData[i])) {
                value = oData[i].join(', ');
            } else if (YAHOO.lang.isObject(oData[i])) {
                for(var col in oData[i]) {
                    value += oData[i][col] + ' / ';
                }
                // remove trailing comma and space
                value = value.substring(0, value.length-2);
            } else {
                value = oData[i];
            }
            html += '<li>' + value + '</li>';
        }
        el.innerHTML = html;
    } else {
        el.innerHTML = "";
    }
};

YAHOO.widget.DataTable.Formatter.showAllowedSPConnections = function(el, oRecord, oColumn, oData) {
    var entityId = oRecord.getData('entityid');
    var html = '<a href="/serviceregistry/allowed-connections/show-for-idp?eid=' + encodeURIComponent(entityId) + '">Allowed SP\'s</a>';
    el.innerHTML += html;
};

YAHOO.widget.DataTable.Formatter.showAllowedIdPConnections = function(el, oRecord, oColumn, oData) {
    var entityId = oRecord.getData('entityid');
    var html = '<a href="/serviceregistry/allowed-connections/show-for-sp?eid=' + encodeURIComponent(entityId) + '">Allowed IdP\'s</a>';
    el.innerHTML += html;
};