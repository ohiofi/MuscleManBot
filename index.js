const express = require('express');
const app = express();
require('dotenv').config();
const Twit = require('twit');
var T = new Twit({
  consumer_key:         process.env.CONSUMER_KEY,
  consumer_secret:      process.env.CONSUMER_SECRET,
  access_token:         process.env.ACCESS_KEY,
  access_token_secret:  process.env.ACCESS_SECRET,
  timeout_ms:           60*1000,  // optional HTTP request timeout to apply to all requests.
  strictSSL:            true,     // optional - requires SSL certificates to be valid.
});

function getTimeline(){
  let options = {
    count: 25,
    exclude_replies: true
  };

  T.get('statuses/home_timeline', options,(err,data,response)=>{
    console.log(data.length);
    tweet_crafter(data);
  })
}

function findGoodCandidateIndex(arr){
  for(let i=0;i<arr.length;i++){
    let txt = arr[i].text;
    if (txt.length > 140){
      console.log("- Too Long - "+arr[i].text);
      continue
    }
    if (!passedBanlist(txt)){
      console.log("- Failed Banlist - "+arr[i].text);
      continue
    }
    if (!containsKeyword(txt)){
      console.log("- No Keyword Found - "+arr[i].text);
      continue
    }
    return i
  }
  return -1
}

function passedBanlist(str){
  str = str.toLowerCase();
  let banlist = process.env.BANLIST.toLowerCase().split(",");
  for (let i = 0; i < banlist.length; ++i) {
    if (str.indexOf(banlist[i]) > -1) {
      return false; // String is present
    }
  }
  return true; // No banlisted strings are present
}

const keywords = [
  " is ",
  " isn't ",
  " isn’t ",
  " will ",
  " won't ",
  " won’t ",
  " wants ",
  " can ",
  " can't ",
  " can’t ",
  " made ",
  " got ",
  " has ",
  " hasn't ",
  " hasn’t ",
  " had ",
  " went "
]

function containsKeyword(str){
  let lowerStr = str;
  for (let i = 0; i < keywords.length; ++i) {
    if(lowerStr.indexOf(keywords[i]) > -1){
      return true
    }
  }
  return false
}
function keywordCut(str){
  let lowerStr = str;
  for (let i = 0; i < keywords.length; ++i) {
    if(lowerStr.indexOf(keywords[i]) > -1){
      return str.slice(lowerStr.indexOf(keywords[i]),str.length)
    }
  }
  return "oops"
}

// yr code here
function punctuationCut(str)
{
  if(str.indexOf("U.S. ") != -1 )// Convert " U.S. " to " US "
  	str = str.replace("U.S. ","US ");
  if(str.indexOf("?") != -1 )// Cut at "?"
    str = str.slice(0,str.indexOf("?"));
  if(str.indexOf(".") != -1 )// Cut at "."
    str = str.slice(0,str.indexOf("."));
  if(str.indexOf("!") != -1 )// Cut at "!"
    str = str.slice(0,str.indexOf("!"));
  if(str.indexOf("|") != -1 )// Cut at "|"
    str = str.slice(0,str.indexOf("|"));
  if(str.indexOf("http") != -1 )// Cut at "!"
    str = str.slice(0,str.indexOf("http"));
  if(str.indexOf("www") != -1 )// Cut at "!"
    str = str.slice(0,str.indexOf("www"));
  if(str.indexOf(":") != -1 )// Cut at ":"
    str = str.slice(0,str.indexOf(":"));
  if(str.indexOf(" himself ") != -1 )// Convert " his " to " her "
    str = str.replace(" himself "," herself ");
  if(str.indexOf(" his ") != -1 )// Convert " his " to " her "
  	str = str.replace(" his "," her ");
  if(str.indexOf(" he ") != -1 )// Convert " he " to " she "
    str = str.replace(" he "," she ");
  if(str.indexOf(" man ") != -1 )// Convert " he " to " she "
    str = str.replace(" man "," woman ");
  str = str.trim();
  return str;
}


function twitterCallback(err, data, response) {
  if(err){
    console.log("Oof. Error. "+err)
  } else {
    console.log("It worked.")
  }
}


  // let myArray = [
  //   {text:"lkjsdf"},
  //   {text:"covid is death virus"},
  //   {text:"steve is going to the mall. Who cares? Really."}
  // ]

function tweet_crafter(myArray){
  let index = findGoodCandidateIndex(myArray);
  if(index == -1){
    return
  }
  let tweetText = "You know who else"+keywordCut(myArray[index].text)
  tweetText = punctuationCut(tweetText);
  tweetText+="? https://twitter.com/"+myArray[index].user.screen_name+"/status/"+myArray[index].id_str;// Append the URL of the @ mention";
  console.log(tweetText);
  let tweetObj = {
    status: tweetText,
    in_reply_to_status_id: myArray[index].id
  };
  T.post('statuses/update', tweetObj, twitterCallback)
}

getTimeline();
setInterval(()=>{
  getTimeline()
}, 1000*60*121); //ms*s*m*h

app.listen(
  process.env.PORT || 3000,
  ()=>console.log("bot running")
);
