module.exports = {
	
	main : {
		options: {
			// noAdvanced: true,
			// compatibility : true,
			// debug : true
			// keepBreaks : true
		},
		files: {
			'<%= app.cssPath %>/events.min.css': [
				'<%= app.cssPath %>/events.css'
			]
		}
	}
};