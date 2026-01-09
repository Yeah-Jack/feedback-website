fetch("/assets/header/index.html")
	.then((response) => response.text())
	.then((data) => {
		document.body.insertAdjacentHTML("afterbegin", data)
	})
