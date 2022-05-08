$('.remove-btn').on('click', (e) => {
    let self = e.target;
    let nodeName = self.nodeName;
    if(nodeName === 'I') {
        self = self.parentNode;
    }
    
    let url = self.getAttribute('data-url');
    let id = self.getAttribute('data-id');

    $.ajax({
        method: "DELETE",
        url: url
    }).done(() => {
        $(`#product-${id}`).remove();
    })
    
})