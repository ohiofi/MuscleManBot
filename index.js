const express = require('express');
const app = express();
require('dotenv').config();
const Twit = require('twit');
// var T = new Twit({
//   consumer_key:         process.env.CONSUMER_KEY,
//   consumer_secret:      process.env.CONSUMER_SECRET,
//   access_token:         process.env.ACCESS_KEY,
//   access_token_secret:  process.env.ACCESS_SECRET,
//   timeout_ms:           60*1000,  // optional HTTP request timeout to apply to all requests.
//   strictSSL:            true,     // optional - requires SSL certificates to be valid.
// });

function getTimeline(){
  let options = {
    count: 25,
    exclude_replies: true
  };
  let timeline = [];
  timeline = T.get('statuses/home_timeline', options, twitterCallback);
  return timeline
}

function findGoodCandidate(arr){
  for(let i=0;i<arr.length;i++){
    let txt = arr[i].text;
    if (!passedBanlist(txt)){
      console.log("Failed Banlist")
      continue
    }

  }
}

function passedBanlist(str){
  str = str.toLowerCase();
  for (var i = 0; i < process.env.BANLIST.length; ++i) {
    if (str.indexOf(process.env.BANLIST[i]) > -1) {
      return false; // String is present
    }
  }
  return true; // No banlisted strings are present
}

// yr code here
function punctuationCut(str)
{
  if(str.indexOf("U.S. ")) != -1 )// Convert " U.S. " to " US "
  	str = str.replace("U.S. ","US ");
  if(str.indexOf("?")) != -1 )// Cut at "?"
    str = str.slice(0,str.indexOf("?"));
  if(str.indexOf(".")) != -1 )// Cut at "."
    str = str.slice(0,str.indexOf("."));
  if(str.indexOf("!")) != -1 )// Cut at "!"
    str = str.slice(0,str.indexOf("!"));
  if(str.indexOf("|")) != -1 )// Cut at "|"
    str = str.slice(0,str.indexOf("|"));
  if(str.indexOf("http")) != -1 )// Cut at "!"
    str = str.slice(0,str.indexOf("http"));
  if(str.indexOf("www")) != -1 )// Cut at "!"
    str = str.slice(0,str.indexOf("www"));
  if(str.indexOf(":")) != -1 )// Cut at ":"
    str = str.slice(0,str.indexOf(":"));
  if(str.indexOf(" himself ")) != -1 )// Convert " his " to " her "
    str = str.replace(" himself "," herself ");
  if(str.indexOf(" his ")) != -1 )// Convert " his " to " her "
  	str = str.replace(" his "," her ");
  if(str.indexOf(" he ")) != -1 )// Convert " he " to " she "
    str = str.replace(" he "," she ");
  if(str.indexOf(" man ")) != -1 )// Convert " he " to " she "
    str = str.replace(" man "," woman ");
  str = trim(str);
  return str;
}


function twitterCallback(err, data, response) {
  if(err){
    console.log("Oof. Error. "+err)
  } else {
    console.log("It worked.")
  }
}

app.listen(
  process.env.PORT || 3000,
  ()=>console.log("bot running")
);
