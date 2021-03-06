import isPlainObject from 'is-plain-object';

import deepFilter from 'deep-filter';

// Format Date of Chart Data
export const formatDate = ( data ) => {
	Object.keys( data['visualizer-series']).map( i => {
		if ( data['visualizer-series'][i].type !== undefined && 'date' === data['visualizer-series'][i].type ) {
			Object.keys( data['visualizer-data']).map( o => {
				return data['visualizer-data'][o][i] = new Date( data['visualizer-data'][o][i]);
			});
		}
	});
	return data;
};

// A fork of deep-compact package as it had some issues
// NOTE: This method is likely to create problems.
// Problem Scenario #1:
// - A table has 5 columns (series). Say the 1st column is Date and others are Numbers.
// - If the 1st columns format (series.format) is provided, DataTable.js gets 6 (0-5) series.
// - BUT if the 1st columns format (series.format) is empty, DataTable.js gets 5 (1-4) series.
// That is why when sending options to DataTable.js, filterChart method has not been used.
const notEmpty = value => {
	let key;

	if ( Array.isArray( value ) ) {
		return 0 < value.length;
	}

	if ( isPlainObject( value ) ) {
		for ( key in value ) {
			return true;
		}

		return false;
	}

	if ( 'string' === typeof value ) {
		return 0 < value.length;
	}

	return null != value;
};

export const compact = value => deepFilter( value, notEmpty );

// Remove chart size-related properies for Chart List
export const filterCharts = value => {
	value.width = '';
	value.height = '';
	value.backgroundColor = {};
	value.chartArea = {};

	return compact( value, notEmpty );
};

// Check if JSON object is valid or not
export const isValidJSON = obj => {
	try {
		JSON.parse( obj );
	} catch ( e ) {
		return false;
	}
	return true;
};

// Convert CSV data to Array
// Source: https://www.bennadel.com/blog/1504-ask-ben-parsing-csv-strings-with-javascript-exec-regular-expression-command.htm
export const CSVToArray = ( strData, strDelimiter ) => {
	strDelimiter = ( strDelimiter || ',' );

	const objPattern = new RegExp(
		( '(\\' + strDelimiter + '|\\r?\\n|\\r|^)' +  '(?:\'([^\']*(?:\'\'[^\']*)*)\'|' + '([^\'\\' + strDelimiter + '\\r\\n]*))' ), 'gi' );

	const arrData = [ [] ];

	let arrMatches = null;

	while ( arrMatches = objPattern.exec( strData ) ) {

		const strMatchedDelimiter = arrMatches[ 1 ];

		if ( strMatchedDelimiter.length && strMatchedDelimiter !== strDelimiter ) {
			arrData.push([]);
		}

		let strMatchedValue;

		if ( arrMatches[ 2 ]) {
			strMatchedValue = arrMatches[ 2 ].replace( new RegExp( '\'\'', 'g' ), '\'' );
		} else {
			strMatchedValue = arrMatches[ 3 ];
		}

		arrData[ arrData.length - 1 ].push( strMatchedValue );
	}

	return ( arrData );
};


export const isChecked = ( settings, param ) => {
    return true === settings[param] || 'true' === settings[param] || '1' === settings[param] || 1 === settings[param];
};
