import $ from 'jquery';
import FormValidate from './modules/FormValidateRules';
import LikedPosts from './modules/LikedPosts';
import urlParser from 'js-video-url-parser/lib/base';
import 'js-video-url-parser/lib/provider/vimeo';
import 'js-video-url-parser/lib/provider/youtube';

$(() => {
  console.log("admin.js is running...");

  new LikedPosts();

  const admin = $(".adminContentJS");
  
  if ($("table a[data-delete]").length || $("a.btn.btn-danger").length) {
    appendModalToBody();
  
    admin.find("table a[data-delete], a.btn.btn-danger").on("click", e => {
      e.preventDefault();
    
      const link = $(e.target);
      const data = parseCsvDash(link.data("delete"));
      const urlToPost = link.attr("href").split("?")[0];
      let title = "";
      let body = "";
    
      if (data.table == "users") {
        title = "Delete User";
        body = `<p>Are you sure you want to delete the user
          <strong class="font-weight-bold">${data.username}</strong>?<br>
          You can not delete the user which has posts, unless
          you delete the user posts first!</p>`;
      
        } else if (data.table == "topics") {
        title = "Delete Topic";
        body = `<p>Are you sure you want to delete the topic
          <strong class="font-weight-bold">${data.name}</strong>?<br>
          If there are posts under this topic you can not delete it,
           unless you delete those posts first!</p>`;
      
      } else if (data.table == "posts") {
        title = "Delete Post";
        body = `<p>Are you sure you want to delete the post <strong class="font-weight-bold">${data.title}</strong>?<br>
        This post will be permanently deleted!</p>`;
      }
    
      currentModal(title, body, data, urlToPost);
    });
  }
  
  /*
   User Edit Form ------------------------------------------------------- */

  if ($("#userEditForm").length) {

    const validate = new FormValidate("userEditForm");
    validate.settings.fieldSize["username"] = { min: 4, max: 20 };
    validate.settings.fieldSize["password"] = { min: 8, max: 20 };
    validate.settings.fieldSize["about_text"] = { min: 30, max: 300 };
    validate.settings.uniqueVal["username"] = true;
    validate.settings.uniqueVal["email"] = true;

    validate.form.on("submit change", async e => {
      e.preventDefault();
  
      console.log("submit, change");
  
      const username = await validate.username("username");
      const email = await validate.email("email");
      const password = await validate.password("password");
      const confirmPassword = await validate.confirmPassword("confirm_password");
      const aboutText = await validate.aboutText("about_text");
  
      if (e.type == "submit" && !validate.hasError()) {
        validate.form.off("submit");
        validate.form.trigger("submit");
      }
      return false;
    })

    $("#about_image").on("change", e => {
      const input = e.target;
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = (e) => {
          $('#aboutImage').attr('src', e.target.result).removeClass("d-none");
        }
        reader.readAsDataURL(input.files[0]);
      }
    });

  }

  /*
   Edit Post Form ------------------------------------------------------------ */

  if ($("#editPostForm").length) {
    const postForm = $("#editPostForm");

    editPostFormElementsBehavior(postForm);

    postForm.find("#image").on("change", e => {
      const input = e.target;
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = (e) => {
          postForm.find("#previewImage").attr("src", e.target.result);
          postForm.find(".preview-image").removeClass("d-block").addClass("d-block");
          postForm.find(".preview-image").attr("data-value", "true");
        }
        reader.readAsDataURL(input.files[0]);
      }
    });

    postForm.find("#video").on("change", e => {
      const targetUrl = $(e.target).val();
      const video = urlParser.parse(targetUrl);
      let videoUrl = "";
      let iframe = "";

      if (video.provider == 'youtube') {
        videoUrl = `//www.youtube.com/embed/${video.id}`;
        iframe = `<iframe id="previewVideo" class="embed-responsive-item"
                    src="${videoUrl}" frameborder="0" allowfullscreen>
                  </iframe>`;
      } else if (video.provider == 'vimeo') {
        videoUrl = `//player.vimeo.com/video/${video.id}`;
        iframe = `<iframe id="previewVideo" class="embed-responsive-item"
                    src="${videoUrl}" frameborder="0" webkitallowfullscreen
                    mozallowfullscreen' allowfullscreen>
                  </iframe>`;
      }
      if (videoUrl) {
        postForm.find(".preview.preview-video .embed-responsive")
                .html(iframe);
        postForm.find(".preview.preview-video")
                .removeClass("d-block")
                .addClass("d-block")
                .attr("data-value", "true");
      } else {
        alert("Sorry, only youtube and vimeo videos are allowed");
      }
    })

  }

  /*
   XHR Post, User, Topic Search -------------------------------------------*/

  if ($(".loadContentJS").length) appendSpinnerToMainContent();

  const pagData = {
    pathname: window.location.pathname,
    type: "search",
    value: "",
    access: $(".loadContentJS").data("access"),
    total: 0,
    page: 1,
    params(type, value, access, total) {
      pagData.type = type;
      pagData.value = value;
      pagData.access = access;
      pagData.total = total;
    },
    pageNum(page=false) {
      if (page) pagData.page = page;
      else return pagData.page;
    },
    script() {
      const pathitems = pagData.pathname.split("/");
      const path = `${pathitems[1]}/${pathitems[2]}`;
      switch (path) {
        case 'staff/posts':
              return "xhr_search_post.php";
        case 'staff/users':
        case 'staff/topics':
              return "xhr_search.php";
        default: 
              return false;
      }
    }
  };

  $("#adminSearchForm").on("submit", e => {
    e.preventDefault();

    const form = $(e.target);
    const type = form.attr("data-type");
    const value = form.find("#s").val();
    const access = $(".loadContentJS").data("access");

    loading(1);
    $.ajax({
      url: server.baseUrl + '/staff/' + pagData.script(),
      type: "POST",
      data: {
        target: access + '_by_' + type,
        data: `type=${type}&value=${value}&access=${access}&pathname=${pagData.pathname}`,
        uid: server.userId
      },
      success: res => {

        loading(0);
        const data = JSON.parse(res);

        loadPostBox(data);
        pagData.params(type, value, access, data[2].total_count);
      },
      error: err => console.log(err)
    })

    return false;
  }) // <-- Admin Search Form

  $(".loadContentJS").on("click", ".click-load", e => {
    e.preventDefault();

    const link = $(e.target);
    const type = link.attr("data-type");
    const value = link.attr("data-value");
    const access = link.attr("data-access");

    if (type && value && access) {
      loading(1);
      $.ajax({
        url: server.baseUrl + '/staff/' + pagData.script(),
        type: 'POST',
        data: {
          target: access + '_by_' + type,
          data: `type=${type}&value=${value}&access=${access}&pathname=${pagData.pathname}`,
          uid: server.userId
        },
        success: res => {

          loading(0);
          const data = JSON.parse(res);

          loadPostBox(data);
          pagData.params(type, value, access, data[2].total_count);

          console.log('pagData.total', pagData.total);
        },
        error: err => console.log(err)
      })
    }

    return false;
  }) // <-- Click-Load Link

  $(".loadContentJS").on("click", ".page-link", e => {
    e.preventDefault();

    const page = getPageNum($(e.target));
    if (!page) return false;

    loading(1);
    $.ajax({
      url: server.baseUrl + '/staff/' + pagData.script(),
      type: 'POST',
      data: {
        target: pagData.access + '_by_' + pagData.type,
        data: `type=${pagData.type}&value=${pagData.value}&access=${pagData.access}&page=${page}&pathname=${pagData.pathname}`,
        uid: server.userId
      },
      success: res => {
        //let timer = setTimeout(() => {
          loading(0);
          const data = JSON.parse(res);

          loadPostBox(data);
          pagData.pageNum(page);
          $('#item-' + page).addClass("active");
          
          //clearTimeout(timer);
        //}, 1200)
      },
      error: err => console.log(err)
    })
  })

}) // <-- jQuery

/*
 * Functions ---------------------------------------------------------*/

function editPostFormElementsBehavior(form) {
  form.find(".form-check-input").on("change", (e) => {
    if (e.target.value == "image") {
      form.find("#image").prop("disabled", false);
      form.find("#video").prop("disabled", true);
      form.find(".media-preview").attr("data-format", "image");
    } else if (e.target.value == "video") {
      form.find("#image").prop("disabled", true);
      form.find("#video").prop("disabled", false);
      form.find(".media-preview").attr("data-format", "video");
    }
    if (form.find(".media-preview").attr("data-format") == "image") {
      form.find(".preview").removeClass("d-none d-block");
      form.find(".preview-video").addClass("d-none");
      if (form.find(".preview-image").attr("data-value") != "false") {
        form.find(".preview-image").addClass("d-block");
      } else {
        form.find(".preview-image").addClass("d-none");
      }
    } else if (form.find(".media-preview").attr("data-format") == "video") {
      form.find(".preview").removeClass("d-none d-block");
      form.find(".preview-image").addClass("d-none");
      if (form.find(".preview-video").attr("data-value") != "false") {
        form.find(".preview-video").addClass("d-block");
      } else {
        form.find(".preview-video").addClass("d-none");
      }
    }
  })
}

function loading(wait=0) {
  if (wait == 1) {
    $(".loadContentJS").hide();
    $(".loading").removeClass("d-none");
  } else if (wait == 0) {
    $(".loading").addClass("d-none");
    $(".loadContentJS").show();
  }
}

function getPageNum(target) {
  let page = false;

  if (target.closest("li").hasClass("active")) {
    console.log('this link is active ' + target.text());
    return false;
  }

  if (target.hasClass("page-link")) {
    if (target.parent().hasClass("active")) {
      return;
    }
  } else if (target.is("span")) {
    target = target.closest(".page-link");
  }

  let url = target.attr("href");

  if (url) {
    const params = url.split("?")[1];
    const paramsArr = params.split("&");
    
    paramsArr.forEach(value => {
      if (value.split("=")[0] == 'page') {
        page = value.split("=")[1];
        $(".pagination li.page-item").removeClass("active");
        $(`#item-${page}`).addClass("active");
        return false;
      }
    });
  }

  return page;
}

function loadPostBox(data) {
  const loadBox = $(".loadContentJS");

  if (data[0] == 'success') {
    loadBox.html("");
    loadBox.html(data[1]);
    loadBox.append(data[2].html);
  } else if (data[0] == 'failed') {
    console.log("failed");
  }
}

function currentModal(title, body, data, urlToPost) {
  const modal = $("#deleteModal");
  modal.on("show.bs.modal", e => {
    modal.find(".modal-title").text(title);
    modal.find(".modal-body").html(body);
    modal.find("form").attr("action", urlToPost);
    modal.find("form input[name='table']").val(data.table);
    modal.find("form button[name='delete']").val(data.id);
  })
  modal.modal();
}

function appendModalToBody() {
  const modal = `<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="deleteModalTitle"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding:.7rem 1rem;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body alert alert-warning mb-0 py-4"></div>
        <div class="modal-footer">
          <button id="modalCancelBtn" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <form action="" method="post" class="my-3">
            <input type="hidden" name="table" value="">
            <button class="btn btn-danger btn-md delete" name="delete" value="">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>`;

  $("body").append(modal);
}

function appendSpinnerToMainContent() {
  const spinner = `<div class="loading d-none">
    <div class="spinner-border" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>`;
  $(".main .main-content").append(spinner);
}

function parseCsvDash(str) {
  const arr = str.split(",");
  const obj = {};
  arr.forEach(elem => {
    const prop = elem.split("-")[0];
    const value = elem.split("-")[1];
    return obj[prop] = value;
  })
  return obj;
}