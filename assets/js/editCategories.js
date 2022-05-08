$('.edit-btn').on('click', (e) => {
    let self = e.target;
    let nodeName = self.nodeName;
    if(nodeName === 'I') {
        self = self.parentNode;
    }
    let inputId = self.getAttribute('data-id');

    $(`.edit-${inputId}`).prop('disabled', false);
    $(`#edit-btn-${inputId}`).hide();
    $(`#valid-edit-btn-${inputId}`).show();
})


$('.valid-edit-btn').on('click', (e) => {
    let self = e.target;
    let nodeName = self.nodeName;
    if(nodeName === 'I') {
        self = self.parentNode;
    }

    let url = self.getAttribute('data-url');
    let id = self.getAttribute('data-id');

    const value = $(`.edit-${id}`).val();

    $.ajax({
        method: "PUT",
        url: url,
        data: value
    }).done(response => {
        if(response.success) {
            $(`.edit-${id}`).prop('disabled', true);
            $(`#valid-edit-btn-${id}`).hide();
            $(`#edit-btn-${id}`).show();
        }
    })
    
})


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
        $(`#category-${id}`).remove();
    })
    
})