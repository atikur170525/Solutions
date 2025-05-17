let a=process.argv.slice(2)
if(!a.length)return console.log("")
let s=a[0],r=""
for(let i=0;i<s.length;++i)
for(let j=i+1;j<=s.length;++j){
let sub=s.slice(i,j)
if(sub.length<=r.length)continue
if(a.every(x=>x.includes(sub)))r=sub
}
console.log(r)
