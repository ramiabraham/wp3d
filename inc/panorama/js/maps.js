/*
* Loads panorama hdri image maps, as well as fields
* for all modals, and xyz coordinates for each
*/

var officeData = { 'panorama': [
{

// exterior panorama
    'mapFile': panorama_globals.exterior_panorama_image_map,
    'navMarkers': [
      { 'target': 1,
        'x': -130.0484355184786,
        'y': 10.396268393623394,
        'z': 1012.357313842974687
      },
    ],
    'markers': [
      {'person': 'ext_modal_1',
        'x': -150.0522836660948,
        'y': 68.33536854966658,
        'z': -798.80745435331792
      },
      { 'person': 'ext_modal_2',
        'x': 304.3501347961816,
        'y': -62.66661678215218,
        'z': -287.3566836379287
      }
    ]

  },
  {

// interior primary hdri map, here's the first instance of wp_localize_script being used.

    'mapFile': panorama_globals.interior_panorama_image_map_1,
    'navMarkers': [
      { 'target': 0,
        'x': -496.80219781041825,
        'y': 16.577589194263247,
        'z': 53.969989690977606
      },
      { 'target': 2,
        'x': -40.50393644709794,
        'y': -99.59872119984476,
        'z': -991.5036468495347
      },
    ],
    'markers': [
      {'person': 'int_modal_1',
        'x': -710.82921204357737,
        'y': 77.8726641829491307,
        'z': -450.5378244518269
      },
      { 'person': 'int_modal_2',
        'x': -197.14559206230575,
        'y': -23.55322535482139,
        'z': 297.86967587528129
      }
    ]

  },
  {
// interior secondary map

    'mapFile': panorama_globals.interior_panorama_image_map_2,
    'navMarkers': [
      { 'target': 0,
        'x': 350.55335882972173,
        'y': -65.065896266279868,
        'z': 300.22722517927485
      }
    ],
    'markers': [
      { 'person': 'int_modal_2',
        'x': 186.35642970111687,
        'y': 19.193904543760027,
        'z': -463.57618051127014
      }
    ]
  }

  ],

//end panorama, start modals

  'people':{
    'ext_modal_1': {
      'name': panorama_globals.modal_1_title,
      'role': panorama_globals.modal_1_description,
      'image': panorama_globals.modal_1_image
    },
    'ext_modal_2': {
      'name': panorama_globals.modal_2_title,
      'role': panorama_globals.modal_2_description,
      'image': panorama_globals.modal_2_image
    },
    'int_modal_1': {
      'name': panorama_globals.interior_modal_1_title,
      'role': panorama_globals.interior_modal_1_description,
      'image': panorama_globals.interior_modal_1_image
    },
    'int_modal_2': {
      'name': panorama_globals.interior_modal_2_title,
      'role': panorama_globals.interior_modal_2_description,
      'image': panorama_globals.interior_modal_2_image
    },
    'int_modal_3': {
      'name': panorama_globals.interior_modal_3_title,
      'role': panorama_globals.interior_modal_3_description,
      'image': panorama_globals.interior_modal_3_image
    }
  }
};