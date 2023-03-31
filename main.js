'use strict'

const avatarPersonal = document.querySelector('.js-items-icon');
const showaMenuAvatarPersonal = document.querySelector('.js-icon-setting-show');
const bellHeader = document.querySelector('.js-hide-bell-header');
const bellShowHeader = document.querySelector('.js-icon-bell-show');
const iconCloseBellNotify = document.querySelector('.js-close-bell-notify')
const showSearchHeader = document.querySelector('.js-hide-search-header')
const searchHeader = document.querySelector('.js-search-header');
const searchStopHeader = document.querySelector('.js-search-header');
const showExport = document.querySelector('.js-show-file-type');
const chooseExport = document.querySelector('.js-choose-file-type');

const closeModalSeeUser = document.querySelector('.js-modal-see-user');
const stopModalSeeUser = document.querySelector('.js-modal-user-detail');
const closeModalEditUser = document.querySelector('.js-modal-edit-user')
const stopModalEditUser = document.querySelector('.js-modal-edit-detail')
const showModalComfirmPassword = document.querySelector('.js-modal-confirm-password')
const stopModalComfirm = document.querySelector('.js-modal-confirm')
const closeModalSaveInfor = document.querySelector('.js-modal-save-infor')
const closeModalDeleteUser = document.querySelector('.js-modal-delete-user');
const closeModalPb = document.querySelector('.js-modal-add-pb')
const closeModalEditPb = document.querySelector('.js-modal-edit-pb')
const closeModalTask = document.querySelector('.js-modal-task-user')
const closeModalNoTask = document.querySelector('.js-modal-no-task')
const closeModalNopDon = document.querySelector('.js-modal-delete-donnop');
const closeModalDuyetDon = document.querySelector('.js-modal-duyet-user');

const body = document.querySelector('body')
const sidebar = document.querySelector('.sidebar-menu')
    // avatar setting

    function showMenuAvatar(event){
        showaMenuAvatarPersonal.classList.toggle('active');
        event.stopPropagation()
    }

    function hideMenuAvatar(){
        showaMenuAvatarPersonal.classList.remove('active');
    }

    if(avatarPersonal != null)
    {
        avatarPersonal.addEventListener('click',showMenuAvatar);
        sidebar.addEventListener('click',hideMenuAvatar);
        body.addEventListener('click',hideMenuAvatar);
        showaMenuAvatarPersonal.addEventListener('click', (event) =>{
            event.stopPropagation();
        })
    }

// Jquery
$(document).ready(function() {
    // menu dropdown
    $('.list-items-user ul').hide();
    $(".list-items-user .click-user").click(function () {
        $(this).parent(".list-items-user").children("ul").slideToggle("2000");
        $('.arrow-right-user').toggleClass('arrow-down')
    });

    $('.list-items-task ul').hide();
    $(".list-items-task .click-task").click(function () {
        $(this).parent(".list-items-task").children("ul").slideToggle("2000");
        $('.arrow-right-task').toggleClass('arrow-down')
    });

    $('.list-items-pb ul').hide();
    $(".list-items-pb .click-pb").click(function () {
        $(this).parent(".list-items-pb").children("ul").slideToggle("2000");
        $('.arrow-right-pb').toggleClass('arrow-down')
    });

    $('.list-items-ngaynghi ul').hide();
    $(".list-items-ngaynghi .click-ngaynghi").click(function () {
        $(this).parent(".list-items-ngaynghi").children("ul").slideToggle("2000");
        $('.arrow-right-ngaynghi').toggleClass('arrow-down')
    });

    // sidebar
    $(".hideSidebar").click(function () {
        $('.submenu-listuser').toggleClass('nav-none-infor')
        $('.arrow-listuser i').toggleClass('arrow-hide')
        $('.sidebar-menu').toggleClass('active');
        $('.header').toggleClass('active')
        $('.section-content-dashboard').toggleClass('active')
        $('.management-user').toggleClass('active')
        $('.logo-name').toggleClass('hidelogoname');
        $('.two-rotate-right').toggleClass('two-rotate-left')
        $('.list-items-user ul').toggleClass('');
        $('.nav-list-title').toggleClass('nav-none-infor')
    });

    $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    
    // gioi han ky tu
    $('.list-task-title').each(function (f) {
        var newstr = $(this).text();
        if(newstr.length < 15){
            limit = newstr;
            return $(this).text(limit);
        }else{
            var limit = newstr.substring(0,16) + '...';
            return $(this).text(limit);
        }
    });

    $('.list-task-name-user').each(function (f) {
        var newstr = $(this).text();
        if(newstr.length < 25){
            limit = newstr;
            return $(this).text(limit);
        }else{
            var limit = newstr.substring(0,26) + '...';
            return $(this).text(limit);
        }
    });

    $('.list-task-content').each(function (f) {
        var newstr = $(this).text();
        if(newstr.length < 80){
            limit = newstr;
            return $(this).text(limit);
        }else{
            var limit = newstr.substring(0,81) + '...';
            return $(this).text(limit);
        }
    });

    
});

// dong mo menu ipad
const sidebarMenu = document.querySelector('.sidebar-menu');
const showSidebarIpad = document.querySelector('.show-sidebar-ipad');
const coatingSidebar = document.querySelector('.coating-sidebar');
const iconShowsidebarVectorFirst = document.querySelector('.icon-showsidebar-vector-first')
const iconShowsidebarVectorLast = document.querySelector('.icon-showsidebar-vector-last')


    function sidebarMenuShow(event){
        sidebarMenu.classList.add('activeipad');
        coatingSidebar.classList.add('active');
        iconShowsidebarVectorLast.classList.add('active');
        iconShowsidebarVectorFirst.classList.add('active');
        event.stopPropagation();
    }

    function sidebarMenuHide(){
        sidebarMenu.classList.remove('activeipad');
        coatingSidebar.classList.remove('active');
        iconShowsidebarVectorLast.classList.remove('active');
        iconShowsidebarVectorFirst.classList.remove('active');
    }

    if(showSidebarIpad != null)
    {
        showSidebarIpad.addEventListener('click',sidebarMenuShow);
        coatingSidebar.addEventListener('click',sidebarMenuHide);
        showSidebarIpad.addEventListener('click',hideMenuAvatar);
        sidebarMenu.addEventListener('click', (event) =>{
            event.stopPropagation();
        });
    }
    
    function showModalSeeUser(data){
        closeModalSeeUser.classList.add('active');
        document.getElementById("modal-name").innerHTML = data['fullname'];
        document.getElementById("modal-PB").innerHTML = data['id_PhongBan'];
        document.getElementById("modal-Chucvu").innerHTML = (data['id_role'] == 1?'Trưởng Phòng':'Nhân Viên')
        document.getElementById("modal-CMND").innerHTML = data['cmnd'];
        document.getElementById("modal-SDT").innerHTML = data['sdt'];
        document.getElementById("modal-email").innerHTML = data['email'];
        document.getElementById("modal-diachi").innerHTML = data['address'];
        document.getElementById("modal-username").innerHTML = data['username'];
        }

    function closeModalSeeuser(){
        closeModalSeeUser.classList.remove('active');
    }

    if(stopModalSeeUser !== null){
        stopModalSeeUser.addEventListener('click', (e) =>{
            e.stopPropagation();
        })
    }

    function closeModalSeeIcon(){
        closeModalSeeUser.classList.remove('active');
    }

    function showModalEdituser(){
        closeModalEditUser.classList.add('active')
    }

    function closeModalEdituser(){
        closeModalEditUser.classList.remove('active')
    }

    if(stopModalEditUser !== null){
        stopModalEditUser.addEventListener('click',(e) =>{
            e.stopPropagation();
        })
    }

    // modal data	
    var model_del_id = ''
    var modal_page =''
    function showModalComfirmPass(data){
        showModalComfirmPassword.classList.add('active')
        document.getElementById("resetpassword-user").innerText = data['fullname']
        document.getElementById("resetpassword-iduser").innerText = data['id_user']
        model_del_id = data['id_user']
        modal_page = data['page']
    }

    function closeModalComfirmPass(){
        showModalComfirmPassword.classList.remove('active')
    }
    function respass_modal()
    {
        document.cookie = "preventres = 1"
        document.location.href = './listuser.php?page='+modal_page+'&resid='+model_del_id;
    }

    if(stopModalComfirm !== null){
        stopModalComfirm.addEventListener('click',(e) =>{
            e.stopPropagation();
        })
    }

    function showModalSaveInfor(){
        closeModalSaveInfor.classList.add('active')
    }

    function closeModalSaveinfor(){
        closeModalSaveInfor.classList.remove('active')
    }

    function showModalDeleteUser(data){
        closeModalDeleteUser.classList.add('active')
        document.getElementById("modal-delete").innerHTML = data['fullname'];
        model_del_id = data['id_user']
        modal_page = data['page']
    }
    function showModalNewPw()
    {
        closeModalDeleteUser.classList.add('active')
    }
    var idtask = ''

    function showModalcancel(data,data2)
    {
        closeModalDeleteUser.classList.add('active')
        document.getElementById("modal-nv").innerHTML = data
        idtask = data2;
    }
    
    function cancelnv()
    {
        document.cookie = "preventdel = 1"
        document.location.href = './listtask.php?idcancel='+idtask;
    }

    function showModalstart(){
        closeModalDeleteUser.classList.add('active')
    }

    function startmiss(data)
    {
        document.cookie = "preventdel = 1"
        document.location.href = './chitiettasknv.php?starttask=' + data +'&idtask=' + data
    }

    function del_modal()
    {
        document.cookie = "preventdel = 1"
        document.location.href = './listuser.php?page='+modal_page+'&delid='+model_del_id;
    }
    
    function closeModalDeleteuser(){
        closeModalDeleteUser.classList.remove('active')
    }
    function closeModalNewPw(){
        closeModalDeleteUser.classList.remove('active')
        document.querySelector('.error-password').style.display = 'none'
        document.querySelector('.error-newpassword').style.display = 'none'
        document.querySelector('.error-retypenewpassword').style.display = 'none'
    }
    function closeModaldanhgia()
    {
        closeModalDeleteUser.classList.remove('active')
    }
    function showModalPb(){
        closeModalPb.classList.add('active')
    }   

    function closeModalpb(){
        closeModalPb.classList.remove('active')
    }  

    var nv_id = '';
    var pb_id = '';
    var tenpb_m = '';
    var motapb_m =''
    function showModalEditPb(data){
        closeModalEditPb.classList.add('active')
        let name = document.getElementById("modal-tenpb");
        name.innerHTML = "Chỉnh sửa Phòng Ban "+data['tenpb'];
        document.getElementById("sopb").value = data['id_PhongBan']
        document.getElementById("tenpb").value = data['tenpb']
        document.getElementById("motapb").value = data['mota']
        pb_id = data['id_PhongBan'];  
        tenpb_m = data['tenpb'];
        motapb_m = data['mota'];
    }


    function closeModalEditpb(){
        closeModalEditPb.classList.remove('active')
        document.getElementById("error-so").style.display = "none";
        document.getElementById("error-ten").style.display = "none";
        document.getElementById("error-mota").style.display = "none";
        document.getElementById("error-sotontai").style.display = "none";
        document.getElementById("sopb").value ="";
        document.getElementById("tenpb").value="";
        document.getElementById("motapb").value="";

    }
    var datetime = ''
    function showModalNoTask(){
        closeModalNoTask.classList.add('active')
        datetime = document.getElementById("giahan").value
    }

    function showModalTaskNV(){
        closeModalNoTask.classList.add('active')
    }
    

    function closeModalNotask(){
        closeModalNoTask.classList.remove('active')
        document.getElementById("error-tieude").style.display = "none";
        document.getElementById("error-mota").style.display = "none";
        document.getElementById("error-file").style.display = "none";
        document.getElementById("error-ngay").style.display = "none";
        document.getElementById("tieude").value ="";
        document.getElementById("mota").value ="";
        document.getElementById("giahan").value =datetime;
        document.getElementById("tep").value ="";
    }

    function closeModalTaskNV(){
        closeModalNoTask.classList.remove('active')
    }
    function closeModalNd(){
        closeModalNoTask.classList.remove('active')
    }
    function showModalNd(){
        closeModalNoTask.classList.add('active')
    }

if(showExport !== null && chooseExport !== null)
{
    function showMenuExport(event){
        chooseExport.classList.toggle('active');
        event.stopPropagation()
    }

    function hideMenuExport(){
        chooseExport.classList.remove('active');
    }


    showExport.addEventListener('click',showMenuExport)
    body.addEventListener('click',hideMenuExport)
    sidebar.addEventListener('click',hideMenuExport)
    avatarPersonal.addEventListener('click',hideMenuExport)
    bellHeader.addEventListener('click',hideMenuExport)
    showSearchHeader.addEventListener('click',hideMenuExport)
    showExport.addEventListener('click',(event) =>{
        event.stopPropagation();
    });
}

//////////////////////////////////////////
    var mql = window.matchMedia('(min-width: 1024px)');
    var mqltb = window.matchMedia('(min-width: 768px) and (max-width: 1023px)');
    var mqlmb = window.matchMedia('(max-width: 767px)');

    function showModalifpb(data){
        closeModalTask.classList.add('active');
        let container =  document.getElementById('table-pb');
        let tp =  document.getElementById('truongphong');
        let ten =  document.getElementById('modal-tenpbNV');
        tp.innerHTML = "Trưởng phòng hiện tại: Chưa Có"
        if(data.length==0)
        {
            
        }
        else
        {
            data.forEach((element) => {
                ten.innerHTML = "Danh sách nhân viên phòng ban "+element['Ten_phongban']
                const row = document.createElement('tr');
                row.className = "title-listuser";
                const button = document.createElement("td");


                const label = document.createElement('label')
                const input = document.createElement('input');
                const span = document.createElement('span');
                
                if(mql.matches)
                {
                    const id = document.createElement("td");
                    const fullname = document.createElement("td");
                    const chucvu = document.createElement("td");
                    id.innerHTML = element['id_PhongBan'];
                    fullname.innerHTML = element['fullname'];
                    
                    if(element['id_role'] == '1')
                    {
                        chucvu.innerHTML = "Trưởng phòng";
                    }
                    else
                    {
                        chucvu.innerHTML = "Nhân viên";
                    }
                    row.appendChild(id);
                    row.appendChild(fullname);
                    row.appendChild(chucvu);
                }
                
                if(mqltb.matches)
                {
                    const fullname = document.createElement("td");
                    const chucvu = document.createElement("td");
                    fullname.innerHTML = element['fullname'];
                    
                    if(element['id_role'] == '1')
                    {
                        chucvu.innerHTML = "Trưởng phòng";
                    }
                    else
                    {
                        chucvu.innerHTML = "Nhân viên";
                    }
                    row.appendChild(fullname);
                    row.appendChild(chucvu);
                }

                if(mqlmb.matches)
                {
                    const fullname = document.createElement("td");
                    fullname.innerHTML = element['fullname'];
                    row.appendChild(fullname);
                }

                button.className = "radio-add-task";
                input.type = "radio";
                
                input.name = "task";
                input.id = "check"+element['id_role']
                input.setAttribute("value",element['id_user']);
                span.className = "checkmark";
                label.className = "container-radio";
                label.appendChild(input);
                label.appendChild(span);
                button.appendChild(label);
                
                row.appendChild(button);

                container.appendChild(row);

                if(element['id_role'] == 1)
                {
                    tp.innerHTML = "Trưởng phòng hiện tại: "+element['fullname'];
                }
            });
            var checbox = document.getElementById("check1");
            if(checbox)
                checbox.checked = true 
        }

        $(".radio-add-task input").click(function(){
            $(".radio-add-task input:checked").each(function(index) {
                nv_id = $(this).val();
                pb_id = $(this).attr('id');
                var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?value=' + $(this).val(); 
                window.history.pushState({ path: refresh }, '', refresh);
            });
        });

    }


    function closeModalifpb()
    {
        const scrollTopModalTask = document.querySelector('.js-table-data-list-task');
        scrollTopModalTask.scrollTop = 0;
        closeModalTask.classList.remove('active')
        let container = document.getElementById("table-pb");
        while (container.firstChild) {
            container.removeChild(container.lastChild);
          }
        var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname;
        window.history.pushState({ path: refresh }, '', refresh);
    }

    function AddTruongPhong(){
        const scrollTopModalTask = document.querySelector('.js-table-data-list-task');
        scrollTopModalTask.scrollTop = 0;
        closeModalTask.classList.remove('active')
        let container = document.getElementById("table-pb");
        while (container.firstChild) {
            container.removeChild(container.lastChild);
          }
        document.cookie = "preventres = 1"
        if(nv_id != '' && pb_id != '')
        {
            document.location.href = './listphongban.php?value=' + nv_id +'&id=' + pb_id;
        }
    }


    function modalluu(data)
    {
        document.getElementById("error-so").style.display = "none";
        document.getElementById("error-ten").style.display = "none";
        document.getElementById("error-mota").style.display = "none";
        document.getElementById("error-sotontai").style.display = "none";
        
        let sopb = document.getElementById("sopb").value.replace(/ /g,'');
        let tenpb = document.getElementById("tenpb").value.replace(/ /g,'');
        let motapb = document.getElementById("motapb").value.replace(/ /g,'');
        let exists = 0;
        let error = [];
        data.forEach((element) => {
            if(sopb == element['id_PhongBan'])
            {
                exists = 1;
                document.getElementById("sopb").value = document.getElementById("sopb").value.replace(/ /g,'');
            }
            if(sopb == pb_id)
            {
                exists = 0;
            }
        })
        
        if(exists ==1 )
        {
            error.push("0");
            document.getElementById("error-sotontai").style.display = "block";
        }
        if(sopb == '') 
        {
            error.push("1");
            document.getElementById("error-so").style.display = "block";
            document.getElementById("sopb").value ="";
        }
        if(tenpb == '')
        {
            error.push("2");
            document.getElementById("error-ten").style.display = "block";
            document.getElementById("tenpb").value ="";
        }
        else
        {
            tenpb =document.getElementById("tenpb").value
        }
        if(motapb == '')
        {
            error.push("3");
            document.getElementById("error-mota").style.display = "block";
        }
        else
        {
            motapb = document.getElementById("motapb").value
        }
        
        if(error.length == 0)
        {
            if(tenpb_m == tenpb && pb_id == sopb && motapb_m == motapb)
            {
                closeModalEditpb();
            }
            else
            {
                document.location.href = './listphongban.php?sopb='+sopb+'&tenpb='+tenpb+'&mota='+motapb+'&idpb='+pb_id;
            }
        }
    }

    function showModalTask(){
        closeModalTask.classList.add('active');

    }

    function closeModaltask(){
        const scrollTopModalTask = document.querySelector('.js-table-data-list-task');
        scrollTopModalTask.scrollTop = 0;
        closeModalTask.classList.remove('active')
    }



    function showModalTask(data,data2,data3,data4){
        closeModalTask.classList.add('active');
        idtask = data4;
        let container =  document.getElementById('table-task');
        let tp =  document.getElementById('nhiemvu');
        tp.innerHTML = "Nhiệm vụ: "+ data3;
        if(data.length>0)
        {
            data.forEach((element) => {
                const row = document.createElement('tr');
                row.className = "title-listuser";

                const button = document.createElement("td");


                const label = document.createElement('label')
                const input = document.createElement('input');
                const span = document.createElement('span');

                if(mql.matches){
                    const fullname = document.createElement("td");
                    const chucvu = document.createElement("td");
                    const phongban = document.createElement("td");
                    fullname.innerHTML = element['fullname'];
                    chucvu.innerHTML = "Nhân Viên"
                    phongban.innerHTML = data2;
                    row.appendChild(fullname);
                    row.appendChild(chucvu);
                    row.appendChild(phongban);
                }

                if(mqltb.matches){
                    const fullname = document.createElement("td");
                    const chucvu = document.createElement("td");
                    fullname.innerHTML = element['fullname'];
                    chucvu.innerHTML = "Nhân Viên"
                    row.appendChild(fullname);
                    row.appendChild(chucvu);
                }

                if(mqlmb.matches){
                    const fullname = document.createElement("td");
                    fullname.innerHTML = element['fullname'];
                    row.appendChild(fullname);
                }

                button.className = "radio-add-task";
                input.type = "radio";
                
                input.name = "task";
                input.id = "check"+element['id_role']
                input.setAttribute("value",element['id_user']);
                span.className = "checkmark";
                label.className = "container-radio";
                label.appendChild(input);
                label.appendChild(span);
                button.appendChild(label);
                           
                row.appendChild(button);

                container.appendChild(row);
            });
        }

        $(".radio-add-task input").click(function(){
            $(".radio-add-task input:checked").each(function(index) {
                nv_id = $(this).val();
                var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname + '?nhanvienid=' + $(this).val() 
                + '&idtask=' + idtask; 
                window.history.pushState({ path: refresh }, '', refresh);
            });
        });
        
    }


    function closeModaltask(){
        const scrollTopModalTask = document.querySelector('.js-table-data-list-task');
        scrollTopModalTask.scrollTop = 0;
        closeModalTask.classList.remove('active')
        let container = document.getElementById("table-task");
        while (container.firstChild) {
            container.removeChild(container.lastChild);
        }
        var refresh = window.location.protocol + '//' + window.location.host + window.location.pathname;
        window.history.pushState({ path: refresh }, '', refresh);
    }

    function luutask()
    {
        const scrollTopModalTask = document.querySelector('.js-table-data-list-task');
        scrollTopModalTask.scrollTop = 0;
        closeModalTask.classList.remove('active')
        if(nv_id != '' && pb_id != '')
        {
            document.location.href = './listphongban.php?value=' + nv_id +'&id=' + pb_id;
        }
    }

    function submittask()
    {
        document.getElementById("error-tieude").style.display = "none";
        document.getElementById("error-mota").style.display = "none";
        document.getElementById("error-file").style.display = "none";
        
        let error = [];
        let tieude = document.getElementById("tieude").value;
        let mota = document.getElementById("mota").value;
        let file = document.getElementById("tep");


        if(tieude == '') 
        {
            error.push("1");
            document.getElementById("error-tieude").style.display = "block";
        }
        if(mota == '')
        {
            error.push("2");
            document.getElementById("error-mota").style.display = "block";
        }
        if (file.files.length > 0) 
        {
            var filePath = file.value;
            var accessfiletype = 
            /(\.rar|\.docx|\.pdf|\.txt|\.zip|\.pptx|\.png|\.jpg)$/i;
            
            if (!accessfiletype.exec(filePath)) {
                error.push("3");
                document.getElementById("error-file").style.display = "block";
                document.getElementById("error-file").innerHTML = "Xin lỗi, file bạn phải hợp lệ với các định dạng jpg, png, rar, docx, pptx, zip, txt, pdf"
            }
        
                for (let i = 0; i <= file.files.length - 1; i++) {
    
                    const fsize = file.files.item(i).size;
                    const filesize = Math.round((fsize / 1024));

                    if (filesize > 1000000) 
                    {
                        error.push("5");
                        document.getElementById("error-file").style.display = "block";
                        document.getElementById("error-file").innerHTML = "File upload, dung lượng quá lớn"
                    } 
                }
        }
        else
        {
            error.push("4");
            document.getElementById("error-file").style.display = "block";
            document.getElementById("error-file").innerHTML = "Vui lòng chọn file trước khi upload"
        }
        if(error.length == 0)
        {
            let data = new FormData();

            data.append("tep",$("#tep").prop("files")[0]);

            let xhr = new XMLHttpRequest();
            xhr.upload.addEventListener("progress", e =>{
                let progress = e.loaded * 100 / e.total
                if(progress)
                {
                    $("input").prop('disabled', true);
                }
                $(".progress").show();
                $("#progress-bar").width(JSON.stringify(progress)+'%');
            })

            xhr.open("POST","chitiettasknv.php",true)
            xhr.send(data);

            document.getElementById("submit").submit();
        }
    }
    function resubmittask()
    {
        document.getElementById("error-tieude").style.display = "none";
        document.getElementById("error-mota").style.display = "none";
        document.getElementById("error-file").style.display = "none";
        document.getElementById("error-ngay").style.display = "none";
        
        let error = [];
        let tieude = document.getElementById("tieude").value;
        let mota = document.getElementById("mota").value;
        let giahan = document.getElementById("giahan").value;
        let file = document.getElementById("tep");

        if(tieude == '') 
        {
            error.push("1");
            document.getElementById("error-tieude").style.display = "block";
        }
        if(mota == '')
        {
            error.push("2");
            document.getElementById("error-mota").style.display = "block";
        }
        if(giahan < datetime)
        {
            error.push("6");
            document.getElementById("error-ngay").style.display = "block";
        }
        if (file.files.length > 0) 
        {
            var filePath = file.value;
            var accessfiletype = 
            /(\.rar|\.docx|\.pdf|\.txt|\.zip|\.pptx|\.png|\.jpg)$/i;
            
            if (!accessfiletype.exec(filePath)) {
                error.push("3");
                document.getElementById("error-file").style.display = "block";
                document.getElementById("error-file").innerHTML = "Xin lỗi, file bạn phải hợp lệ với các định dạng jpg, png, rar, docx, pptx, zip, txt, pdf"
            }
        
                for (let i = 0; i <= file.files.length - 1; i++) {
    
                    const fsize = file.files.item(i).size;
                    const filesize = Math.round((fsize / 1024));

                    if (filesize > 1000000) 
                    {
                        error.push("5");
                        document.getElementById("error-file").style.display = "block";
                        document.getElementById("error-file").innerHTML = "File upload, dung lượng quá lớn"
                    } 
                }
        }
        if(error.length == 0)
        {
            if(document.querySelector("#tep").files.length > 0)
            {
                let data = new FormData();

                data.append("tep",$("#tep").prop("files")[0]);
    
                let xhr = new XMLHttpRequest();
                xhr.upload.addEventListener("progress", e =>{
                    let progress = e.loaded * 100 / e.total
                    if(progress)
                    {
                        $("input").prop('disabled', true);
                    }
                    $(".progress").show();
                    $("#progress-bar").width(JSON.stringify(progress)+'%');
                })
    
                xhr.open("POST","chitiettask.php",true)
                xhr.send(data);
            }

            document.getElementById("submit").submit();
        }
    }
    function complete(data)
    {
        document.cookie = "preventdel = 1"
        document.getElementById("error-danhgia").style.display = "none";
        let status = 0;
        if(document.getElementById('bad').checked)
        {
            status = 1;
        }
        else if(document.getElementById('ok').checked)
        {
            status = 2;
        }
        else if(document.getElementById('good').checked)
        {
            status = 3;
        }
        if(status == 0)
        {
            document.getElementById("error-danhgia").style.display = "block";
        }
        else
        {
            document.location.href = './chitiettask.php?idtask='+data+'&danhgia='+status
        }
    }
             
    function closeModaldanhgia()
    {
        document.getElementById("error-danhgia").style.display = "none";
        closeModalDeleteUser.classList.remove('active')
        document.getElementById('bad').checked = false;
        document.getElementById('ok').checked = false;
        document.getElementById('good').checked = false;
    }
    function showModaldanhgia()
    {
        closeModalDeleteUser.classList.add('active')
    }
    
if(document.querySelector("#start") != null)
    {
        document.querySelector("#start").addEventListener("change",e =>{

            var start = e.target.value;
            var end = document.querySelector("#end").value;
        
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd

            if(start < today)
            {
                $(".error-ngaybatdau").show()
                $(".error-ngaybatdau").text("Ngày bắt đầu lớn hơn hoặc bằng ngày hiện tại")
            }
            else
            {
                $(".error-ngaybatdau").hide()
            }
            
            if(document.querySelector("#end").value != '')
            {
                const date1 = new Date(start);
                const date2 = new Date(end);

                const diffTime = date2 - date1;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                var tongngaynghi = document.querySelector('#tongngay')
                tongngaynghi.value = diffDays;

                if(diffTime < 0)
                {
                    $(".error-tongngaynghi").show()
                    $(".error-tongngaynghi").text("Ngày bắt đầu phải bé hơn ngày kết thúc")
                }
                else if(tongngaynghi.value > parseInt(document.querySelector('#songayconlai').attributes['value'].value))
                {
                    $(".error-tongngaynghi").show()
                    $(".error-tongngaynghi").text("Tổng ngày nghỉ không được lớn hơn số ngày còn lại")
                }
                else if(tongngaynghi.value > 15 && document.querySelector('#user').attributes['value'].value == 'user1')
                {
                    $(".error-tongngaynghi").show()
                    $(".error-tongngaynghi").text("Ngày nghỉ của bạn không được vượt quá 15 ngày")
                }
                else if(tongngaynghi.value > 12 && document.querySelector('#user').attributes['value'].value == 'user2')
                {
                    $(".error-tongngaynghi").show()
                    $(".error-tongngaynghi").text("Ngày nghỉ của bạn không được vượt quá 12 ngày")
                }
                else
                {
                    $(".error-tongngaynghi").hide()
                }
            }
        })
    }

    if(document.querySelector("#end") != null )
    {
        document.querySelector("#end").addEventListener("change",e =>{
            
            if(document.querySelector("#start").value != '')
            {
                var start = document.querySelector("#start").value;
                var end = e.target.value;
                
                const date1 = new Date(start);
                const date2 = new Date(end);
                const diffTime = date2 - date1;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                var tongngaynghi = document.querySelector('#tongngay')
                tongngaynghi.value = diffDays;
    
                if(diffTime <= 0)
                {
                    $(".error-tongngaynghi").show()
                    $(".error-tongngaynghi").text("Ngày bắt đầu phải bé hơn ngày kết thúc")
                }
                else if(tongngaynghi.value > parseInt(document.querySelector('#songayconlai').attributes['value'].value))
                {
                    $(".error-tongngaynghi").show()
                    $(".error-tongngaynghi").text("Tổng ngày nghỉ không được lớn hơn số ngày còn lại")
                }
                else if(tongngaynghi.value > 15 && document.querySelector('#user').attributes['value'].value == 'user1')
                {
                    $(".error-tongngaynghi").show()
                    $(".error-tongngaynghi").text("Ngày nghỉ của bạn không được vượt quá 15 ngày")
                }
                else if(tongngaynghi.value > 12 && document.querySelector('#user').attributes['value'].value == 'user2')
                {
                    $(".error-tongngaynghi").show()
                    $(".error-tongngaynghi").text("Ngày nghỉ của bạn không được vượt quá 12 ngày")
                }
                else
                {
                    $(".error-tongngaynghi").hide()
                }
            }
        })
    }

    if(document.querySelector("#document") != null)
    {
        document.querySelector("#document").addEventListener("change",e => {

            let myFile = e.target.files[0];
            let extensions= ['jpg','png','rar','docx','pptx','zip','txt','pdf'];
            let file_exten = myFile.name.split('.').pop();
            let file_size = myFile.size

            if(!extensions.includes(file_exten))
            {
                $("#error").show()
                $("#error").text("Xin lỗi, file bạn phải hợp lệ với các định dạng jpg, png, rar, docx, pptx, zip, txt, pdf");

            } 
            else if (file_size > 1073741824)
            {
                $("#error").show()
                $("#error").text("Xin lỗi, tệp của bạn quá lớn, kích thước tối đa là 1 GB")
            }
            else
            {
                $("#error").hide()
            }
        })
    }

    function upload(){

        var n = [];

        if(document.querySelector("#document").files.length > 0)
        {
            let data = new FormData();

            data.append("file",$("#document").prop("files")[0]);

            let xhr = new XMLHttpRequest();
            xhr.upload.addEventListener("progress", e =>{
                let progress = e.loaded * 100 / e.total
                if(progress)
                {
                    $("input").prop('disabled', true);
                }
                $(".progress").show();
                $("#progress-bar").width(JSON.stringify(progress)+'%');
            })


            if(document.querySelector('#error').style.display == "none")
            {
                xhr.open("POST","listngaynghi.php",true)
                xhr.send(data);
            }
        }

        if(document.querySelector("#start").value == '')
        {
            n.push('1');
            document.getElementById("error-ngaybatdau").style.display = "block"
            document.getElementById("error-ngaybatdau").innerHTML = "Chọn ngày nghỉ";
        }
        if(document.querySelector("#end").value == '')
        {
            n.push('2');
            document.getElementById("error-ngayketthuc").style.display = "block"
            document.getElementById("error-ngayketthuc").innerHTML = "Chọn ngày kết thúc"
        }
        if(document.querySelector("#lydo").value == '')
        {
            n.push('3');
            document.getElementById("error-lydo").style.display = "block"
            document.getElementById("error-lydo").innerHTML = "Nhập lý do"
        }

        if(document.querySelector(".error-tongngaynghi").style.display != "none")
        {
            n.push('4');
            document.querySelector(".error-tongngaynghi").style.display = "block"
        }

        if(document.querySelector('#error').style.display != "none")
        {
            n.push('5');
            document.getElementById("error").style.display = "block"
        }

        console.log(n);

        if(n.length == 0)
        {
            document.getElementById("submit").submit();
        }
    }
             
    function showModalNopdon(data,fullname){
        closeModalNopDon.classList.add('active')
        document.getElementById('nguoigui').innerHTML = '&nbsp'+fullname;
        data.forEach(element => {
            document.getElementById('ngaybatdau').innerHTML = '&nbsp'+covertdate(element['ngaybatdau']);
            document.getElementById('ngayketthuc').innerHTML = '&nbsp'+covertdate(element['ngayketthuc']);
            document.getElementById('tongsongay').innerHTML = '&nbsp'+element['tongngaynghi'];
            document.getElementById('lydo').innerHTML ='&nbsp'+ element['mota'];
            if(element['tepdinhkem'] != '')
            {
                document.getElementById('showfile').style.display = "block"
                document.getElementById('dinhkem').innerHTML = element['tepdinhkem'];
                 var Extension = element['tepdinhkem'].substring(
                    element['tepdinhkem'].lastIndexOf('.') + 1).toLowerCase();
                document.getElementById('downdonlist').setAttribute( 'href', "../files/nopdon/DON"+element['id']+"."+Extension);
                document.getElementById('downdonlist').setAttribute( 'download', element['tepdinhkem'] );
            }
        });
    }

    function closeModalDonnop(){
        closeModalNopDon.classList.remove('active')
    }

    var don_id = '';

    function showModalDDon(data,fullname){
        closeModalDuyetDon.classList.add('active')
        document.getElementById('nguoigui').innerHTML = '&nbsp'+fullname;
        data.forEach(element => {
            document.getElementById('ngaybatdau').innerHTML = '&nbsp'+covertdate(element['ngaybatdau']) ;
            document.getElementById('ngayketthuc').innerHTML = '&nbsp'+covertdate(element['ngayketthuc']);
            document.getElementById('tongsongay').innerHTML = '&nbsp'+element['tongngaynghi'];
            document.getElementById('lydo').innerHTML = '&nbsp'+element['mota'];
            don_id = element['id_nn']
            if(element['trangthai'] == 1 || element['trangthai'] == 2)
            {
                var status = document.getElementById('status');
                while (status.firstChild) {
                    status.removeChild(status.lastChild);
                  }
            }
            else
            {
                var status = document.getElementById('status');
                if(!status.hasChildNodes())
                {

                    var div = document.createElement('div');
                    var div1 = document.createElement('div');
                    var div2 = document.createElement('div');
                    var buttton1 = document.createElement('button');
                    var buttton2 = document.createElement('button');

                    div.className = "form-input-extend disflex-btn-dayoff";
                    div1.className = "btn-no-task";
                    div2.className = "btn-yes-task";

                    div1.innerHTML = '<button onclick = "refusedDon()" type="button">Không đồng ý</button>'
                    div2.innerHTML = '<button onclick = "AcceptedDon()" type="button">Đồng ý</button>'

                    div.appendChild(div1)
                    div.appendChild(div2)

                    status.appendChild(div)
                }
            }

            if(element['tepdinhkem'] != '' || element['tepdinhkem'] != null)
            {
                document.getElementById('showfiledon').style.display = "block"
                document.getElementById('dinhkemdon').innerHTML = element['tepdinhkem'];
                var Extension = element['tepdinhkem'].substring(
                    element['tepdinhkem'].lastIndexOf('.') + 1).toLowerCase();
                document.getElementById('downdon').setAttribute( 'href', "../files/nopdon/DON"+element['id']+"."+Extension);
                document.getElementById('downdon').setAttribute( 'download', element['tepdinhkem'] );
            }
        });
    }
    function closeModalDDon(){
        closeModalDuyetDon.classList.remove('active')
    }

    function AcceptedDon()
    {
        closeModalDuyetDon.classList.remove('active')

        document.cookie = "preventdel = 1"
        document.location.href = './duyetngaynghi.php?acceptid=' + don_id;
    }

    function refusedDon()
    {
        closeModalDuyetDon.classList.remove('active')
        
        document.cookie = "preventres = 1"

        document.location.href = './duyetngaynghi.php?refuseid=' + don_id;
    }

    if(document.querySelector("#ngay") != null)
    {
        document.querySelector("#ngay").addEventListener("change",e =>{

            var deadline = new Date(e.target.value)

            var dddl = String(deadline.getDate()).padStart(2, '0');
            var mmdl = String(deadline.getMonth() + 1).padStart(2, '0');

            var dl = deadline.getFullYear()+'-'+mmdl+'-'+dddl+' '+deadline.toLocaleTimeString('it-IT');

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd +' '+today.toLocaleTimeString('it-IT');

            if(dl <= today)
            {
                console.log(today)
                $(".error-ngay").show()
                $(".error-ngay").text("Vui lòng đặt deadline lớn hơn ngày hiện tại")
            }
            else
            {
                $(".error-ngay").hide()
            }
        })
    }

    if(document.querySelector("#documenttask") != null)
    {
        document.querySelector("#documenttask").addEventListener("change",e => {
        
            let myFile = e.target.files[0];
            let extensions= ['jpg','png','rar','docx','pptx','zip','txt','pdf'];
            let file_exten = myFile.name.split('.').pop();
            let file_size = myFile.size
    
            if(!extensions.includes(file_exten))
            {
    
                $("#errortask").show()
                $("#errortask").text("Xin lỗi, file bạn phải hợp lệ với các định dạng jpg, png, rar, docx, pptx, zip, txt, pdf");
    
            } 
            else if (file_size > 1073741824)
            {
    
                $("#errortask").show()
                $("#errortask").text("Xin lỗi, tệp của bạn quá lớn, kích thước tối đa là 1 GB")
            }
            else
            {
                $("#errortask").hide()
            }
        })
    }

function uploadtask()
{
    closeModalPb.classList.remove('active')
    var n = [];
    if(document.querySelector("#documenttask").files.length > 0)
    {
        let data = new FormData();

        data.append("file",$("#documenttask").prop("files")[0]);

        let xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", e =>{
            let progress = e.loaded * 100 / e.total
            if(progress)
            {
                $("input").prop('disabled', true);
            }
            $(".progress").show();
            $("#progress-bar").width(JSON.stringify(progress)+'%');
        })


        if(document.querySelector('#errortask').style.display == "none")
        {
            $("#errortask").hide()
            document.querySelector('.js-modal-add-pb').hidden = true
            xhr.open("POST","listtask.php",true)
            xhr.send(data);
        }
        else
        {
            n.push('1');
            document.getElementById("errortask").style.display = "block"
        }

    }
    if(document.querySelector('.error-ngay').style.display != "none")
    {
        n.push('2');
    }
    if(n.length == 0)
    {
        document.getElementById("submittask").submit();
    }
}
var imgerror = "";
var loadimg = function(event) {
    document.getElementById('error-file').style.display = "none";
    var file = event.target.files[0]['name'];
    var scr = event.target.files[0];
    var output = document.getElementById('anh');
    var Extension = file.substring(
        file.lastIndexOf('.') + 1).toLowerCase();
    if (Extension == "gif" || Extension == "png" || Extension == "bmp"
    || Extension == "jpeg" || Extension == "jpg")
    {
        output.src = URL.createObjectURL(scr);
        output.onload = function() {
        URL.revokeObjectURL(output.src) 
        }
        imgerror = 1;
    }
    else
    {
        output.src = URL.createObjectURL(scr);
        output.onload = function() {
        URL.revokeObjectURL(output.src) 
        }
        document.getElementById('error-file').style.display = "block";
        document.getElementById('error-file').innerHTML ="Chỉ được chọn các định dạng sau: gif, png, bmp, jpeg, jpg";
        imgerror = 0;
    }
  };
  function luuanh()
  {
    if(imgerror =="")
    {
        document.getElementById('error-file').style.display = "block";
        document.getElementById('error-file').innerHTML ="Bạn chưa chọn ảnh!";
    }
    else if(imgerror == 1)
    {
        document.getElementById("change-img").submit();
    }
  }
  function covertdate(date)
  {
    const split = date.split('-');
    var newdate = split[2] + "/" + split[1] + "/" + split[0];
    return newdate;
  }