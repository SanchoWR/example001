function message(node, message) {
    const div = $(`<div class="error">${message}</div>`);
    node.after(div);
}

function isEmpty(fieldValue, node, fieldName) {
    if (fieldValue === "") {
        message(node, `Please enter your ${fieldName}`);
        return true;
    }

    return false;
}
