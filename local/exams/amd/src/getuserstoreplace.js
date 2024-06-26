define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    return {
        processResults: function(selector, results) {
            var options = [];

            $.each(results.data, function(index, response) {
                options.push({
                    value: response.id,
                    label: response.fullname
                });
            });
            return options;
        },
        transport: function(selector, query, callback) {
            var el = $(selector),
           
            type = el.data('type');
            replacinguserid = el.data('replacinguserid');
            rootid = el.data('rootid');
            fieldid = el.data('fieldid');
            Ajax.call([{
                methodname: 'local_exams_tobereplacedusers',
                args: {query:query,type: type,replacinguserid: replacinguserid, rootid: rootid,fieldid: fieldid}
            }])[0].then(callback).catch(Notification.exception);
        }
    };
});
