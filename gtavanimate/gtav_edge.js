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
            id:'Rectangle',
            type:'rect',
            rect:['0px','0px','1000px','200px','auto','auto'],
            fill:["rgba(62,60,60,1.00)"],
            stroke:[0,"rgba(0,0,0,1)","none"]
         },
         {
            id:'Buzzard-TBOGT-front',
            type:'image',
            rect:['1017px','7px','412px','236px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"Buzzard-TBOGT-front.png",'0px','0px'],
            transform:[[],['-15deg'],['0deg']]
         },
         {
            id:'explosion',
            type:'image',
            rect:['880px','209px','96px','82px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"explosion.png",'0px','0px']
         },
         {
            id:'explosionCopy',
            type:'image',
            rect:['880px','209px','96px','82px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"explosion.png",'0px','0px']
         },
         {
            id:'explosionCopy2',
            type:'image',
            rect:['880px','209px','96px','82px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"explosion.png",'0px','0px']
         },
         {
            id:'explosionCopy3',
            type:'image',
            rect:['880px','209px','96px','82px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"explosion.png",'0px','0px']
         },
         {
            id:'explosionCopy4',
            type:'image',
            rect:['880px','209px','96px','82px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"explosion.png",'0px','0px']
         },
         {
            id:'Buzzard-GTAV-rear-screenshot',
            type:'image',
            rect:['-205px','0px','1253px','208px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"Buzzard-GTAV-rear-screenshot.png",'0px','0px']
         },
         {
            id:'Gta-v-p996_jet_copy',
            type:'image',
            rect:['-6px','-141px','1013px','379px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"Gta-v-p996_jet%20copy.jpg",'0px','0px']
         },
         {
            id:'gtavCopy',
            type:'image',
            rect:['153px','54px','314px','92px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"gtav.png",'0px','0px']
         },
         {
            id:'pilotsCopy',
            type:'image',
            rect:['500px','47px','348px','119px','auto','auto'],
            fill:["rgba(0,0,0,0)",im+"pilots.png",'0px','0px']
         }],
         symbolInstances: [

         ]
      },
   states: {
      "Base State": {
         "${_explosion}": [
            ["style", "top", '184px'],
            ["style", "height", '31.92146611866px'],
            ["style", "opacity", '0'],
            ["style", "left", '916px'],
            ["style", "width", '37.416664123535px']
         ],
         "${_Buzzard-GTAV-rear-screenshot}": [
            ["style", "height", '208px'],
            ["style", "top", '211px'],
            ["style", "left", '-242.02px'],
            ["style", "width", '1252.8833007812px']
         ],
         "${_explosionCopy3}": [
            ["style", "top", '160px'],
            ["style", "height", '39.25px'],
            ["style", "opacity", '0'],
            ["style", "left", '436.17px'],
            ["style", "width", '45.987590072481px']
         ],
         "${_Gta-v-p996_jet_copy}": [
            ["style", "top", '211px'],
            ["style", "height", '378.87109375px'],
            ["style", "left", '-6px'],
            ["style", "width", '1012.946158661px']
         ],
         "${_explosionCopy}": [
            ["style", "top", '234px'],
            ["style", "height", '31.92146611866px'],
            ["style", "opacity", '0'],
            ["style", "left", '839px'],
            ["style", "width", '37.416664123535px']
         ],
         "${_gtavCopy}": [
            ["style", "top", '100px'],
            ["style", "opacity", '0'],
            ["style", "left", '155px']
         ],
         "${_explosionCopy2}": [
            ["style", "top", '218px'],
            ["style", "height", '31.92146611866px'],
            ["style", "opacity", '0'],
            ["style", "left", '641px'],
            ["style", "width", '37.416664123535px']
         ],
         "${_Buzzard-TBOGT-front}": [
            ["style", "top", '-47.71px'],
            ["transform", "rotateZ", '-15deg'],
            ["style", "height", '236.33104282924px'],
            ["style", "left", '1061px'],
            ["style", "width", '412px']
         ],
         "${_Stage}": [
            ["color", "background-color", 'rgba(255,255,255,1)'],
            ["style", "overflow", 'hidden'],
            ["style", "height", '200px'],
            ["style", "width", '1000px']
         ],
         "${_Rectangle}": [
            ["color", "background-color", 'rgba(62,60,60,1.00)']
         ],
         "${_pilotsCopy}": [
            ["style", "top", '70px'],
            ["style", "opacity", '0'],
            ["style", "left", '500px']
         ],
         "${_explosionCopy4}": [
            ["style", "top", '160px'],
            ["style", "height", '39.25px'],
            ["style", "opacity", '0'],
            ["style", "left", '255.17px'],
            ["style", "width", '45.987590072481px']
         ]
      }
   },
   timelines: {
      "Default Timeline": {
         fromState: "Base State",
         toState: "",
         duration: 5738,
         autoPlay: true,
         timeline: [
            { id: "eid169", tween: [ "style", "${_gtavCopy}", "top", '54px', { fromValue: '100px'}], position: 2250, duration: 1657, easing: "easeInOutQuad" },
            { id: "eid167", tween: [ "style", "${_gtavCopy}", "left", '155px', { fromValue: '155px'}], position: 2250, duration: 0 },
            { id: "eid159", tween: [ "style", "${_Buzzard-GTAV-rear-screenshot}", "left", '-233.02px', { fromValue: '-242.02px'}], position: 3325, duration: 1362, easing: "easeInOutQuad" },
            { id: "eid174", tween: [ "style", "${_pilotsCopy}", "top", '100px', { fromValue: '70px'}], position: 2250, duration: 1657, easing: "easeInOutQuad" },
            { id: "eid175", tween: [ "style", "${_pilotsCopy}", "top", '54px', { fromValue: '100px'}], position: 3907, duration: 1831, easing: "easeInOutQuad" },
            { id: "eid162", tween: [ "style", "${_pilotsCopy}", "opacity", '0', { fromValue: '0'}], position: 2250, duration: 0 },
            { id: "eid172", tween: [ "style", "${_pilotsCopy}", "opacity", '1', { fromValue: '0'}], position: 3907, duration: 1831, easing: "easeInOutQuad" },
            { id: "eid184", tween: [ "style", "${_Gta-v-p996_jet_copy}", "left", '-6px', { fromValue: '-6px'}], position: 0, duration: 2250, easing: "easeInOutQuad" },
            { id: "eid158", tween: [ "style", "${_Buzzard-GTAV-rear-screenshot}", "top", '0px', { fromValue: '211px'}], position: 3325, duration: 1362, easing: "easeInOutQuad" },
            { id: "eid168", tween: [ "style", "${_gtavCopy}", "opacity", '1', { fromValue: '0'}], position: 2250, duration: 1657, easing: "easeInOutQuad" },
            { id: "eid185", tween: [ "style", "${_Gta-v-p996_jet_copy}", "top", '-140.87px', { fromValue: '211px'}], position: 0, duration: 2250, easing: "easeInOutQuad" }         ]
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
})(jQuery, AdobeEdge, "EDGE-58455798");
