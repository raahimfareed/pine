console.log("Hello, World");

const btn = document.querySelector("button");
const name = document.getElementById("name");

btn.addEventListener("click", () => {
    const toChange = document.getElementById("toChange");

    if (name.value.trim().length == 0) {
        return;
    }

    toChange.textContent = `Hey, ${name.value.trim()}!`;
});
