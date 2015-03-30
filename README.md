# Purpose
Pipeline comments parser for FOSCommentBundle.
Imagine that you need to integrate [ExerciseHTMLPurifierBundle parser](https://github.com/FriendsOfSymfony/FOSCommentBundle/blob/master/Resources/doc/9a-markup_htmlpurifier.md), hashtag parser, user mention parser... it's better to have several parsers where each is responsible for their actions. That's why I decided to write this bundle. This bundle is written for make chain (pipeline) of parser classes.

#Usage

First, configure parser classes as services. You don't have to use all parsers that this bundle provides. The main parser of this bundle is PipelineParser which provides pipeline for parser classes.

```yaml
    markup.mention_parser:
        #This is implementation of simple user mentioning parser like Twitter does
        class: Smilesrg\PipelineParserBundle\Parser\UserMentionParser
        # If you don't plan to use this parser somewhere in the code, it's better to make it private.
        # this should increase service container performance
        public: false
        arguments:
            - @fos_user.user_manager # FOS User Manager
            - @router # Router itself
            - "user_profile_show" # The route for showing user profile
            - "/\B\@([\w\-@]+)/im" # The regular expression pattern for user mentioning

    # See https://github.com/FriendsOfSymfony/FOSCommentBundle/blob/master/Resources/doc/9a-markup_htmlpurifier.md
    markup.exercise_html_purifier:
        class: FOS\CommentBundle\Markup\HtmlPurifier
        arguments: [ @exercise_html_purifier.default ]
```

Then, you have to define PipelineParser as a service and configure it with parsers.

```yaml
    markuper.pipeline_parser:
        class: Smilesrg\PipelineParserBundle\Parser\PipelineParser
        calls:
            - [addToPipeline, ["@markup.exercise_html_purifier"]]
            - [addToPipeline, ["@markup.mention_parser"]]
```

The order of parsers is important. First parser that has been added to pipeline is executed first.


The last step is to use pipeline parser as described in [documentation](https://github.com/FriendsOfSymfony/FOSCommentBundle/blob/master/Resources/doc/9-using_a_markup_parser.md)
```yaml
# app/config/config.yml

fos_comment:
    service:
        markup: markuper.pipeline_parser # The pipeline parser service
```
