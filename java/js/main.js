// JavaScript Document
  var rounds;

    rounds = [


        //-- ronda 1
        [

            {
                player1: { name: "Player 111", winner: true, ID: 111,},
                player2: { name: "Player 211", ID: 211 }
            },

            {
                player1: { name: "Player 112", winner: true, ID: 112 },
                player2: { name: "Player 212", ID: 212 }
            },

            {
                player1: { name: "Player 113", winner: true, ID: 113 },
                player2: { name: "Player 213", ID: 213 }
            },

            {
                player1: { name: "Player 114", winner: true, ID: 114 },
                player2: { name: "Player 214", ID: 214 }
            },

            {
                player1: { name: "Player 115", winner: true, ID: 115,},
                player2: { name: "Player 215", ID: 215 }
            },

            {
                player1: { name: "Player 116", winner: true, ID: 116 },
                player2: { name: "Player 216", ID: 216 }
            },

            {
                player1: { name: "Player 117", winner: true, ID: 117 },
                player2: { name: "Player 217", ID: 217 }
            },

            {
                player1: { name: "Player 118", winner: true, ID: 118 },
                player2: { name: "Player 218", ID: 218 }
            },
        ],

        //-- ronda 2
        [

            {
                player1: { name: "Player 111", winner: true, ID: 111 },
                player2: { name: "Player 212", ID: 212 }
            },

            {
                player1: { name: "Player 113", winner: true, ID: 113 },
                player2: { name: "Player 214", ID: 214 }
            },

            {
                player1: { name: "Player 115", winner: true, ID: 115 },
                player2: { name: "Player 216", ID: 216 }
            },

            {
                player1: { name: "Player 117", winner: true, ID: 117 },
                player2: { name: "Player 218", ID: 218 }
            },
        ],

        //-- ronda 3
        [

            {
                player1: { name: "Player 111", winner: true, ID: 111 },
                player2: { name: "Player 113", ID: 113 }
            },

            {
                player1: { name: "Player 115", winner: true, ID: 115 },
                player2: { name: "Player 218", ID: 218 }
            },
        ],

        //-- ronda 4
        [

            {
                player1: { name: "Player 113", winner: true, ID: 113 },
                player2: { name: "Player 218", winner: true, ID: 218 },
            },
        ],
        //-- Champion
        [

            {
                player1: { name: "Player 113", winner: true, ID: 113 },
            },
        ],

    ];

    var titles = ['Round 1', 'Round 2', 'Round 3', 'Round 4', 'Round 5'];
	
	$('.brackets').hover(function() {
		$(this).css('cursor','default');
	});

    ;(function($){

        $(".brackets").brackets({
            titles: titles,
            rounds: rounds,
            color_title: '#169BFF',
            border_color: '#169BFF',
            color_player: 'white',
            bg_player: '#444',
            color_player_hover: '#444',
            bg_player_hover: 'orange',
            border_radius_player: '30px',
            border_radius_lines: '30px',
        });

    })(jQuery);