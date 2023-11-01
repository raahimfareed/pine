console.log("Hello via Bun!");

const form = document.getElementById("form");
form.addEventListener("submit", (e) => {
  e.preventDefault();
  const heading = document.getElementById("toChange");
  const name = document.getElementById("name").value.trim();
  if (name.length === 0) {
    heading.innerText = `Hey`;
    return;
  }
  heading.innerText = `Hey, ${name}!`;
});
