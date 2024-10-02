(function(wp) {
    const { registerBlockType } = wp.blocks;
    const { InspectorControls } = wp.blockEditor;
    const { TextControl } = wp.components;
    const { createElement } = wp.element;

    registerBlockType('your-namespace/video-display-block', {
        title: 'Video Display Block',
        category: 'media',
        icon: 'video-alt3',
        attributes: {
            videoUrl: {
                // type: 'string',
                source: 'meta',
                meta: '_testimonial_video'
            }
        },
        edit: function(props) {
            const { attributes, setAttributes } = props;
            const { videoUrl } = attributes;
            console.log(props, attributes);

            return createElement(
                'div',
                {},
                createElement(
                    InspectorControls,
                    {},
                    createElement(TextControl, {
                        label: 'Video URL',
                        value: videoUrl,
                        onChange: (url) => setAttributes({ videoUrl: url })
                    })
                ),
                videoUrl ? (
                    createElement('video', {
                        controls: true,
                        src: videoUrl,
                        style: { width: '100%' }
                    })
                ) : (
                    createElement('p', {}, 'No video URL set.')
                )
            );
        },
        save: function(props) {
            const { attributes } = props;
            const { videoUrl } = attributes;

            return createElement(
                'div',
                {},
                videoUrl ? (
                    createElement('video', {
                        controls: true,
                        src: videoUrl,
                        style: { width: '100%' }
                    })
                ) : null
            );
        }
    });
})(window.wp);
