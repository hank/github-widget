/*  GitHub Badge, version 1.2.3
 *  (c) 2008 Dr Nic Williams
 *
 *  GitHub Badge is freely distributable under
 *  the terms of an MIT-style license.
 *  For details, see the web site: http://github.com/drnic/github-badges/tree/master
 *
 *--------------------------------------------------------------------------*/

var GithubBadge = {
  Version: '1.2.3'
};

(function($){
  $.color = $.color || {};

  // Arguments:
  // color: supported format - 'rgb(nnn, nnn, nnn)'
  $.color.almostBlack = function(color) {
    var colorParts = color.match(/rgb\((\d+),\s*(\d+),\s*(\d+)\s*\)/);
    if (!colorParts) return false;
    var combo = parseInt(colorParts[1])/255 * parseInt(colorParts[2])/255 * parseInt(colorParts[3])/255;
    return combo < 0.3;
  };
})(jQuery);
var GitHubBadge = GitHubBadge || {};
GitHubBadge.buildUserBadge = function(username) {
  (function($){
    var title = ("GITHUB_TITLE" in window && GITHUB_TITLE) || 'My projects';
    var sf = ("GITHUB_SHOWFOOTER" in window) ? GITHUB_SHOWFOOTER : true;
    $('#github-badge')
      .empty()
      .buildHeader(title, username)
      .buildBody(username);
    if(sf) $('#github-badge').buildFooter();
  })(jQuery);
  GitHubBadge.requestUserInfo(username);
};

GitHubBadge.loadUserInfo = function(data) {
  (function($){
    var template = $.template(
      "<li class='public clickable'>"
      +  "<img src='http://github.com/images/icons/public.png' alt='public'>"
      +  "<strong><a href='${url}'>${name}</a></strong>"
      +  "<div class='description'>${description}</div>"
      +"</li>"
    );
    var list = $("<div class='repos'><ul id='repo_listing'></ul></div>");
    $('#github-badge .body')
      .empty()
      .append(list);
    list = list.find('ul');
    orderedRepos = data.user.repositories.sort(function(repo1, repo2) {
      var properties = ['network', 'watched'];
      for (var i=0; i < properties.length; i++) {
        var comparison = GitHubBadge.compareReposProperty(repo1, repo2, properties[i]);
        if (comparison != 0) return comparison;
      };
      return data.user.repositories.indexOf(repo2) - data.user.repositories.indexOf(repo1);
    })
    $.each(orderedRepos, function(index) {
      list.append(template, this);
    });
    var showLimit = window.GITHUB_LIST_LENGTH || 10;

		var showAllName = ("GITHUB_SHOW_ALL" in window && GITHUB_SHOW_ALL) || 'Show all';
    var showMore = $("<div><a href='#' class='more'>" + showAllName + " (" + orderedRepos.length + ")</a></div>")
      .find('a')
      .click(function(event) {
        $('#github-badge .body li').show();
        $('#github-badge .more').hide();
        return false;
      });

    $('#github-badge .body li')
    .click(function(event) {
      $(event.currentTarget).find('.description').toggle();
    })
    .find('.description')
      .hide()
      .end()
    .filter(':gt(' + (showLimit - 1) + ')').hide() // hide extras
    if ($('#github-badge .body li').is(':hidden'))
      $('#github-badge .body').append(showMore);

  })(jQuery);
};

GitHubBadge.compareReposProperty = function(repo1, repo2, property) {
  if ((property in repo1) && !(property in repo2)) return -1;
  if (!(property in repo1) && (property in repo2)) return 1;
  if ((property in repo1) && (property in repo2)) {
    return repo2[property] - repo1[property];
  }
  return 0;
};


GitHubBadge.requestUserInfo = function(username) {
  GitHubBadge.Launcher.requestContent(
    "http://github.com/api/v1/json/" + username + "?callback=GitHubBadge.loadUserInfo");
};

(function($){
  $.fn.buildBody = function() {
    return this.append($("<div class='body'>loading...</div>"));
  };

  $.fn.beforeHeader = function() {
    var head = ("GITHUB_BEFORE_HEADER" in window) ? GITHUB_BEFORE_HEADER : "";
    return this.append($(head));
  };

  $.fn.buildHeader = function(title, username) {
    var head = ("GITHUB_HEAD" in window) ? GITHUB_HEAD : "div";
    var template = $.template(
      "<" + head + " class='github-header'>${title} ("
      +   "<a href='http://github.com/${username}'>${username}</a>)"
      + "</" + head + ">")
    return this.append(template, { title: title, username: username });
  };

  $.fn.afterHeader = function() {
    var head = ("GITHUB_AFTER_HEADER" in window) ? GITHUB_AFTER_HEADER : "";
    return this.append($(head));
  };

  $.fn.buildFooter = function() {
    return this.append($(
      "<div class='git-footer'>"
        + "<a href='http://github.com/hank/github-widget'>GitHub Widget</a>"
        + " | "
        + "Written by <a href='http://ralree.com'>Hank</a>, "
        + "<a href='http://drnicwilliams.com'>Dr Nic</a>"
      + "</div>"
      + "</fieldset>"
      ));
  };
})(jQuery);
