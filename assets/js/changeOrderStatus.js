$('.order-status').on('change', (e) => {
    let self = e.target;
    let url = self.getAttribute('data-url');
    $.ajax({
        method: "PUT",
        url: url,
        data: self.value
    })
})