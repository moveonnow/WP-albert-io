(function() {
    tinymce.PluginManager.add('ow_voting_button', function( editor, url ) {
        editor.addButton( 'ow_voting_button', {
            title: 'Insert Voting Shortcodes',
            type: 'menubutton',
            icon: 'ow_button-own-icon',
            menu: [
                {
                    text: 'Show Contestants',
                    value: '[showcontestants id=]',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },                 
                {
                    text: 'Add Contestants',
                    value: '[addcontestants id=]',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
                {
                    text: 'Profile Screen',
                    value: '[profilescreen]',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
                {
                    text: 'Show All Contestants',
                    value: '[showallcontestants]',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
                {
                    text: 'Upcoming Contestants',
                    value: '[upcomingcontestants id]',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
                {
                    text: 'End Contestants',
                    value: '[endcontestants id]',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                }
           ]
        });
    });
})();