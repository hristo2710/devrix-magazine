const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;
const { ToggleControl } = wp.components;
const { useSelect, useDispatch } = wp.data;
const { createElement } = wp.element;

// Article settings panel for the editor
function ArticleSettings() {
    const postType = useSelect(
        (select) => select('core/editor').getCurrentPostType(),
        []
    );

    const meta = useSelect(
        (select) => select('core/editor').getEditedPostAttribute('meta'),
        []
    );

    const { editPost } = useDispatch('core/editor');

    if (postType !== 'article' || !meta) {
        return null;
    }

    function updateMeta(key, value) {
        editPost({
            meta: {
                [key]: value,
            },
        });
    }

    return createElement(
        PluginDocumentSettingPanel,
        {
            name: 'article-settings',
            title: 'Article Settings',
        },

        createElement(ToggleControl, {
            label: 'Featured Article',
            checked: !!meta.is_featured,
            onChange: (value) => updateMeta('is_featured', value),
        }),

        createElement(ToggleControl, {
            label: 'Breaking News',
            checked: !!meta.is_breaking,
            onChange: (value) => updateMeta('is_breaking', value),
        })
    );
}

registerPlugin('devrix-article-settings', {
    render: ArticleSettings,
});