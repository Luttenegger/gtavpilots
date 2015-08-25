/**
 * Adobe Edge: symbol definitions
 */
(function($, Edge, compId){
//images folder
var im='images/';

var fonts = {};


var resources = [
];
var symbols = {
"stage": {
   version: "1.0.0",
   minimumCompatibleVersion: "0.1.7",
   build: "1.0.0.185",
   baseState: "Base State",
   initialState: "Base State",
   gpuAccelerate: false,
   resizeInstances: false,
   content: {
         dom: [
         {
            id:'gtabg',
            type:'image',
            rect:['0','0','2000px','1125px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"gtabg.png",'0px','0px']
         },
         {
            id:'light',
            type:'image',
            rect:['469px','173px','49px','49px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"light.png",'0px','0px']
         }],
         symbolInstances: [

         ]
      },
   states: {
      "Base State": {
         "${_Stage}": [
            ["color", "background-color", 'rgba(255,255,255,1)'],
            ["style", "width", '2000px'],
            ["style", "height", '1125px'],
            ["style", "overflow", 'hidden']
         ],
         "${_light}": [
            ["style", "top", '172.67px'],
            ["style", "opacity", '1'],
            ["style", "left", '468.61px']
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 4000,
         autoPlay: true,
         timeline: [
            { id: "eid3", tween: [ "style", "${_light}", "opacity", '0', { fromValue: '1'}], position: 0, duration: 2000, easing: "easeInQuad" },
            { id: "eid5", tween: [ "style", "${_light}", "opacity", '1', { fromValue: '0.000000'}], position: 2000, duration: 2000, easing: "easeOutQuad" }         ]
      }
   }
}
};


Edge.registerCompositionDefn(compId, symbols, fonts, resources);

/**
 * Adobe Edge DOM Ready Event Handler
 */
$(window).ready(function() {
     Edge.launchComposition(compId);
});
})(jQuery, AdobeEdge, "EDGE-1768291");
