console.log("Hello via Bun!");
const o = document.getElementById("form");
o.addEventListener("submit", (t) => {
  t.preventDefault();
  const e = document.getElementById("toChange"), n = document.getElementById("name").value.trim();
  if (n.length === 0) {
    e.innerText = "Hey";
    return;
  }
  e.innerText = `Hey, ${n}!`;
});
