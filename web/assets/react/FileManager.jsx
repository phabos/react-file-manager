import $ from 'jquery';
import React from 'react';
import {render} from 'react-dom';
import axios from 'axios';
import Croppie from 'croppie';
import Dropzone from 'dropzone';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import RaisedButton from 'material-ui/RaisedButton';
import AppBar from 'material-ui/AppBar';
import IconButton from 'material-ui/IconButton';
import NavigationClose from 'material-ui/svg-icons/navigation/close';
import FlatButton from 'material-ui/RaisedButton';
import FontIcon from 'material-ui/FontIcon';
import {GridList, GridTile} from 'material-ui/GridList';
import Pagination from 'material-ui-pagination';

class FileManager extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            files: [],
            current: 0,
            total: 0,
            resize: null
        };
        this.crop = this.crop.bind(this);
        this.close = this.close.bind(this);
        this.closeResizer = this.closeResizer.bind(this);
        this.handlePageChange = this.handlePageChange.bind(this);
    }
    componentDidMount() {
        const { uploadurl, dropzoneContainer } = this.props;
        var testDropzone = new Dropzone(dropzoneContainer, {
            url: `${uploadurl}`,
            maxFilesize: 2
        });
        testDropzone.on('success', (file) => {
           this.getList();
        });
        $(window).on('showList', () => this.getList());
    }
    edit(event, file) {
        event.preventDefault();
        const { resizerContainer, resizerWindow } = this.props;
        $(resizerContainer).show();
        const el = document.querySelector(resizerWindow);
        el.innerHTML = '';
        const resize = new Croppie(el, {
            viewport: { width: 100, height: 100 },
            boundary: { width: 300, height: 300 },
            showZoomer: false,
            enableResize: true,
            enableOrientation: true
        });
        resize.bind({
            url: `${file}`
        });
        this.setState({
            resize: resize
        });
    }
    crop(e) {
        e.preventDefault();
        const { uploadurl, resizerContainer } = this.props;
        this.state.resize.result('base64').then((base64) => {
            let params = new URLSearchParams();
            params.append('croppedImg', base64);
            axios.post(`${uploadurl}`, params
            ).then((response) => {
                this.getList();
                $(resizerContainer).hide();
            }).catch((error) => {
                alert('error');
            })
        });
    }
    close(e) {
        e.preventDefault();
        const { fileManager } = this.props;
        $(fileManager).hide();

    }
    closeResizer(e) {
        e.preventDefault();
        const { resizerContainer } = this.props;
        $(resizerContainer).hide();
    }
    select(e, title) {
        e.preventDefault();
        $(window).trigger( 'imageSelected', [{imgSrc: title}] );
    }
    getList() {
        const { listurl, fileManager } = this.props;
        const { current } = this.state;

        if( $(fileManager).css('display') !== 'none' ) {
            axios.get(`${listurl}?skip=${current}`).then((response) => {
                this.setState({
                    files: response.data.files,
                    total: response.data.total
                });
            })
            .catch((error) => {
                alert('an error occured');
            });
        }
    }
    toggleDDArea(e) {
        e.preventDefault();
        const { dropzoneContainer } = this.props;
        $(dropzoneContainer).toggle();
    }
    handlePageChange(page) {
        this.setState({
            current: page
        }, () => this.getList() );
    }
    render () {
        const { files, total, current } = this.state;
        const { uploadurl } = this.props;

        const styles = {
            title: {
                cursor: 'pointer',
            },
            root: {
                display: 'flex',
                flexWrap: 'wrap',
                justifyContent: 'space-around',
                marginTop: '30px'
            },
            gridList: {
                width: '100%',
                height: 450,
                overflowY: 'auto'
            },
            form: {
                marginTop: '30px',
                display: 'none'
            },
            button: {
                margin: 12,
            }
        };

        return (
            <MuiThemeProvider>
                <div>
                    <AppBar
                        title={<span style={styles.title}>File manager</span>}
                        iconElementLeft={<IconButton onClick={(e) => this.close(e) }><NavigationClose /></IconButton>}
                        iconElementRight={<IconButton onClick={(e) => this.toggleDDArea(e) }><FontIcon className="material-icons">file_download</FontIcon></IconButton>}
                    />
                    <div className="clearfix"></div>
                    <div className="container">
                        <form style={styles.form} action={uploadurl}
                            className="dropzone"
                            id="file-manager-dropzone"></form>
                        <div className="clearfix"></div>
                        <div id="resizer-container" style={{ display:'none' }}>
                            <div id="resizer"></div>
                            <RaisedButton
                                onClick={(e) => this.crop(e) }
                                label="Cropit"
                                style={styles.button} />
                            <RaisedButton
                                onClick={(e) => this.closeResizer(e) }
                                label="Close"
                                style={styles.button} />
                        </div>
                        <div className="clearfix"></div>
                        <div style={styles.root}>
                            <GridList
                              cellHeight={180}
                              style={styles.gridList}
                              cols={3}
                            >
                                { files.map((file, index) => {
                                    return (
                                        <GridTile
                                            key={`file-${index}`}
                                            title={<span onClick={(event) => { this.select(event, file.title) }}>{`${ file.title }`}</span>}
                                            actionIcon={ <FontIcon className="material-icons" style={{color: '#fff'}} onClick={(event) => { this.edit(event, file.title) }}>edit</FontIcon> }
                                            >
                                            <img src={`${ file.title }`} />
                                        </GridTile>
                                    );
                                    })
                                }
                            </GridList>
                            <Pagination
                                total = { total }
                                current = { current }
                                display = { total }
                                onChange = { (number) => this.handlePageChange(number) }
                            />
                        </div>
                    </div>
                </div>
            </MuiThemeProvider>
        );
    }
}

$(document).ready(function($) {
    $('body').append('<div id="file-manager" style="display:none;"></div>');
    render(
        <FileManager
            uploadurl={settings.upload}
            listurl={settings.list}
            dropzoneContainer='#file-manager-dropzone'
            resizerContainer='#resizer-container'
            resizerWindow='#resizer'
            fileManager='#file-manager' />,
        document.getElementById('file-manager')
    );

    $('.file-manager').on('click', function(event) {
        event.preventDefault();
        $('#file-manager').toggle();
        $(window).trigger( 'showList' );
    });

    $(window).on( 'imageSelected', function(e, src) {
        console.log('listener');
        console.log(src.imgSrc);
    });
});
