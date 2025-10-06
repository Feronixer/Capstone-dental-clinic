document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobile-menu-button-content-management');
    const mobileMenu = document.getElementById('mobile-menu-content-management');
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
});

function setEditServiceData(id, icon_fa, service, price, description) {
    document.getElementById('editServiceId').value = id;
    document.getElementById('editServiceIcon').value = icon_fa;
    document.getElementById('editServiceName').value = service;
    document.getElementById('editServicePrice').value = price;
    document.getElementById('editServiceDescription').value = description;

    document.getElementById('editServiceForm').action = "/admin/content-management/services/" + id;

}

function setDeleteServiceId(id) {
    document.getElementById('deleteServiceId').value = id;
    document.getElementById('deleteServiceForm').action = "/admin/content-management/services/"+ id;
}

document.addEventListener('hidden.bs.modal', function () {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
});


function setEditAnnouncementData(id, title, body) {
    document.getElementById('editAnnouncementId').value = id;
    document.getElementById('editAnnouncementTitle').value = title;
    document.getElementById('editAnnouncementBody').value = body;

    // Set correct form action
    document.getElementById('editAnnouncementForm').action = "/admin/content-management/announcement/" + id;
}

function setEditAnnouncementData(id, title, body) {
    document.getElementById('editAnnouncementId').value = id;
    document.getElementById('editAnnouncementTitle').value = title;
    document.getElementById('editAnnouncementBody').value = body;

    // Set correct form action
    document.getElementById('editAnnouncementForm').action =
        "/admin/content-management/announcement/update/" + id;
}


function setEditMailData(id, structure, preview) {
    document.getElementById('editMailId').value = id;
    document.getElementById('editMailStructure').value = structure;
    document.getElementById('editMailPreview').value = preview;

    document.getElementById('editMailForm').action = "/admin/content-management/mail/" + id;
}
