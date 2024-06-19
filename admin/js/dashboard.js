const VENDOR_SELECT2_OPTIONS = {
    ajax: {
        url: BASE_URL + '/search/vendors',
        dataType: 'json'
    }
};
const PRODUCT_SELECT2_OPTIONS = {
    ajax: {
        url: BASE_URL + '/search/products',
        dataType: 'json'
    }
};
function updatePurchaseNames() {
    //update names
    let index = -1;
    $("#purchases-box .purchase-row").each(function () {
        index++;
        const obj = $(this);
        obj.find(".purchase-items").each(function () {
            const objN = $(this);
            let nameVal = `purchases[${index}][${objN.attr("data-name")}]`;
            objN.attr("name", nameVal);
        });
    });
    //reinitialize select2
    $("#purchases-box .purchaseProductTemp").removeClass("purchaseProductTemp").addClass("purchaseProduct");
    $(".purchaseProduct").select2(PRODUCT_SELECT2_OPTIONS);
}

$(document).on("click", "#addMorePurchases", function () {
    $("#purchases-box").append($("#purchases-box-clone").html());
    updatePurchaseNames();
});

$(document).on("click", ".removePurchase", function () {
    $(this).closest(".purchase-row").remove();
    updatePurchaseNames();
});

updatePurchaseNames();

$(document).ready(function () {
    if ($(".vendorSearch").length > 0) {
        $(".vendorSearch").select2(VENDOR_SELECT2_OPTIONS);
    }
});

function getNewVendors(duration) {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            if (document.querySelector(`#${duration}`) != null) {
                document.querySelector(`#${duration}`).innerHTML = JSON.parse(
                    xhr.responseText
                ).length;
            }
        }
    };
    xhr.open("GET", `${BASE_URL}/admin/vendors/new-this-${duration}`, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send();
}

const vendorList = document.querySelector("#vendorFilterList");
if (vendorList != null) {
    document
        .querySelector("#searchVendor")
        .addEventListener("mouseenter", function () {
            vendorList.style.display = "block";
        });
    vendorList.addEventListener("mouseleave", function () {
        this.style.display = "none";
    });
    vendorList.addEventListener("mouseenter", function () {
        this.style.display = "block";
    });
}
if (document.querySelector("#searchVendor") != null) {
    document.querySelector("#searchVendor").addEventListener("keyup", function (e) {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                const list = JSON.parse(xhr.responseText);
                const filterList = document.querySelector("#vendorFilterList");
                filterList.innerHTML = " ";
                if (list.length > 0) {
                    for (let l of list) {
                        const a = document.createElement("a");
                        a.innerText = l.name;
                        a.href = `/admin/vendors/${l.id}`;
                        filterList.appendChild(a);
                    }
                }
            }
        };
        xhr.open("POST", "/admin/vendors/search", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader(
            "X-CSRF-TOKEN",
            document.querySelector('input[name="_token"]').value
        );
        xhr.send(JSON.stringify({ search: e.target.value }));
    });
}

getNewVendors("week");
getNewVendors("month");

