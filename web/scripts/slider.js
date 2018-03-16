$(function () {
    var primarySelected = "airplane";

    function changeBackground() {
        var selectedType = this.value,
            block = $(".search-section:first"),
            thisId = $(this).attr("id");

        block.toggleClass(primarySelected + " " + selectedType);
        primarySelected = selectedType;
        $("input[name=tickets-type-selected]").val(selectedType);

        $(".tickets-type-section label").removeClass("checked");
        $("label[for=" + thisId + "]").addClass("checked");
    }

    function showTicketsList() {
        var parentElem = $(".tickets-type-section"),
            icon = $(".icon");
        if(!parentElem.hasClass("opened") && !parentElem.hasClass("closed")){
            parentElem.addClass("opened");
            icon.addClass("icon-opened");
            return;
        }
        parentElem.toggleClass("opened closed");
        icon.toggleClass("icon-opened icon-closed");
    }

    $(".show-type-section").on("click", showTicketsList);
    $("input[name=tickets-type]").on("change", changeBackground);

});